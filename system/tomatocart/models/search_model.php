<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Search Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-search-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Search_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get search result
     *
     * @param $filters
     */
    public function get_result($filters = array())
    {
        $sql = 'select distinct p.*, pd.*, m.*, i.image, if(s.status, s.specials_new_products_price, null) as specials_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price, f.products_id as featured_products_id';

        $has_price_set = (isset($filters['price_from']) || isset($filters['price_to']));
        $price_with_tax = isset($filters['with_tax']) && ($filters['with_tax'] == '1');

        if ($has_price_set && $price_with_tax)
        {
            $sql .= ', sum(tr.tax_rate) as tax_rate';
        }

        $sql .= 
        	' from ' . $this->db->protect_identifiers('products', TRUE) . ' p ' . 
        	' left join ' . $this->db->protect_identifiers('manufacturers', TRUE) . ' m using(manufacturers_id) ' . 
        	' left join ' . $this->db->protect_identifiers('specials', TRUE) . ' s on (p.products_id = s.products_id) ' . 
        	' left join ' . $this->db->protect_identifiers('products_frontpage', TRUE) . ' f on (p.products_id = f.products_id) ' . 
        	' left join ' . $this->db->protect_identifiers('products_images', TRUE) . ' i on (p.products_id = i.products_id and i.default_flag = 1)';

        if ($has_price_set && $price_with_tax)
        {
            $sql .= ' left join ' . $this->db->protect_identifiers('tax_rates', TRUE) . ' tr on p.products_tax_class_id = tr.tax_class_id left join ' . $this->db->protect_identifiers('zones_to_geo_zones', TRUE) . ' gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = 0 or gz.zone_country_id = ' . $filters['country_id'] . ') and (gz.zone_id is null or gz.zone_id = 0 or gz.zone_id = ' . $filters['zone_id'] . ')';
        }

        $sql .= ', ' . $this->db->protect_identifiers('products_description', TRUE) . ' pd, ' . $this->db->protect_identifiers('categories', TRUE) . ' c, ' . $this->db->protect_identifiers('products_to_categories', TRUE) . 'p2c';
        $sql .= ' where p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = ' . lang_id() . ' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id';

        //has the category
        if (isset($filters['subcategories']) && !empty($filters['subcategories']))
        {
            $sql .= ' and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and p2c.categories_id in (' . implode(',', $filters['subcategories']) . ')';
        }
        else if (isset($filters['category']) && !empty($filters['category']))
        {
            $sql .= ' and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = ' . lang_id() . ' and p2c.categories_id = ' . $filters['category'];
        }

        //has manufacturer
        if (isset($filters['manufacturer']) && !empty($filters['manufacturer']))
        {
            $sql .= ' and m.manufacturers_id = ' . $filters['manufacturer'];
        }

        //has keywords
        if (isset($filters['keywords']) && !empty($filters['keywords']))
        {
            $sql .= ' and (pd.products_name like "%' . $this->db->escape_like_str($filters['keywords']) . '%" or pd.products_description like "%' . $this->db->escape_like_str($filters['keywords']) . '%")';
        }

        //has price from
        if (isset($filters['price_from']) && !empty($filters['price_from']))
        {
            $filters['price_from'] = $filters['price_from'];
        }

        //has price to
        if (isset($filters['price_to']) && !empty($filters['price_to']))
        {
            $filters['price_to'] = $filters['price_to'];
        }

        //display price with tax
        if ($price_with_tax)
        {
            if (isset($filters['price_from']) && ($filters['price_from'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= ' . $filters['price_from'] . ')';
            }

            if (isset($filters['price_to']) && ($filters['price_to'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= ' . $filters['price_to'] . ')';
            }
        }
        else
        {
            if (isset($filters['price_from']) && ($filters['price_from'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) >= ' . $filters['price_from'] . ')';
            }

            if (isset($filters['price_to']) && ($filters['price_to'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) <= ' . $filters['price_to'] . ')';
            }
        }

        if ($has_price_set && $price_with_tax)
        {
            $sql .= ' group by p.products_id, tr.tax_priority';
        }
        
        //sort: name, price, sku, rating
        $order_by = 'pd.products_name';
        if (isset($filters['sort'])) {
            $entries = array('name', 'price', 'model');
            
            list($entry, $sort) = explode('|', $filters['sort']);
            
            if (in_array($entry, $entries)) {
                switch ($entry) {
                    case "name":
                        $order_by = 'pd.products_name';
                        break;
                    case "price":
                        $order_by = 'p.products_price';
                        break;
                    case "sku":
                        $order_by = 'p.products_sku';
                        break;
                }
            }
            
            if (in_array($sort, array('asc', 'desc'))) {
                $order_by .= ' ' . $sort;
            }
        }

        $sql .= ' order by ' . $order_by . ' limit ' . $filters['page'] * $filters['per_page'] . ',' . $filters['per_page'];

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }


    /**
     * Get search result
     *
     * @param $filters
     */
    public function count_products($filters = array())
    {
        $sql = 'select count(distinct p.products_id) as total';

        $has_price_set = (isset($filters['price_from']) || isset($filters['price_to']));
        $price_with_tax = isset($filters['with_tax']) && ($filters['with_tax'] == '1');

        if ($has_price_set && $price_with_tax)
        {
            $sql .= ', sum(tr.tax_rate) as tax_rate';
        }

        $sql .= ' from ' . $this->db->protect_identifiers('products', TRUE) . ' p left join ' . $this->db->protect_identifiers('manufacturers', TRUE) . ' m using(manufacturers_id) left join ' . $this->db->protect_identifiers('specials', TRUE) . ' s on (p.products_id = s.products_id) left join ' . $this->db->protect_identifiers('products_images', TRUE) . ' i on (p.products_id = i.products_id and i.default_flag = 1)';

        if ($has_price_set && $price_with_tax)
        {
            $sql .= ' left join ' . $this->db->protect_identifiers('tax_rates', TRUE) . ' tr on p.products_tax_class_id = tr.tax_class_id left join ' . $this->db->protect_identifiers('zones_to_geo_zones', TRUE) . ' gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = 0 or gz.zone_country_id = ' . $filters['country_id'] . ') and (gz.zone_id is null or gz.zone_id = 0 or gz.zone_id = ' . $filters['zone_id'] . ')';
        }

        $sql .= ', ' . $this->db->protect_identifiers('products_description', TRUE) . ' pd, ' . $this->db->protect_identifiers('categories', TRUE) . ' c, ' . $this->db->protect_identifiers('products_to_categories', TRUE) . 'p2c';
        $sql .= ' where p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = ' . lang_id() . ' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id';

        //has the category
        if (isset($filters['subcategories']) && !empty($filters['subcategories']))
        {
            $sql .= ' and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and p2c.categories_id in (' . implode(',', $filters['subcategories']) . ')';
        }
        else if (isset($filters['category']) && !empty($filters['category']))
        {
            $sql .= ' and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = ' . lang_id() . ' and p2c.categories_id = ' . $filters['category'];
        }

        //has manufacturer
        if (isset($filters['manufacturer']) && !empty($filters['manufacturer']))
        {
            $sql .= ' and m.manufacturers_id = ' . $filters['manufacturer'];
        }

        //has keywords
        if (isset($filters['keywords']) && !empty($filters['keywords']))
        {
            $sql .= ' and (pd.products_name like "%' . $this->db->escape_like_str($filters['keywords']) . '%" or pd.products_description like "%' . $this->db->escape_like_str($filters['keywords']) . '%")';
        }

        //has price from
        if (isset($filters['price_from']) && !empty($filters['price_from']))
        {
            $filters['price_from'] = $filters['price_from'];
        }

        //has price to
        if (isset($filters['price_to']) && !empty($filters['price_to']))
        {
            $filters['price_to'] = $filters['price_to'];
        }

        //display price with tax
        if ($price_with_tax)
        {
            if (isset($filters['price_from']) && ($filters['price_from'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= ' . $filters['price_from'] . ')';
            }

            if (isset($filters['price_to']) && ($filters['price_to'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= ' . $filters['price_to'] . ')';
            }
        }
        else
        {
            if (isset($filters['price_from']) && ($filters['price_from'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) >= ' . $filters['price_from'] . ')';
            }

            if (isset($filters['price_to']) && ($filters['price_to'] > 0))
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) <= ' . $filters['price_to'] . ')';
            }
        }

        if ($has_price_set && $price_with_tax)
        {
            $sql .= ' group by p.products_id, tr.tax_priority';
        }

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row['total'];
        }

        return 0;
    }
}

/* End of file search_model.php */
/* Location: ./system/tomatocart/models/search_model.php */