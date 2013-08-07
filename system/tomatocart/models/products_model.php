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
 * Products Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-account-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Products_Model extends CI_Model
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
     * Check whether the product id is valid or not
     * 
     * @access public
     * @param $id
     * @return mixed
     */
    public function parse_products_id($id)
    {
        if (is_numeric($id)) 
        {
            $result = $this->db->select('products_id')->from('products')->where('products_id', $id)->where('products_status', '1')->get();
        }
        else
        {
            $result = $this->db->select('p.products_id')
            ->from('products as p')
            ->join('products_description as pd', 'p.products_id = pd.products_id', 'inner')
            ->where('pd.products_friendly_url', $id)
            ->where('p.products_status', '1')
            ->where('pd.language_id', lang_id())
            ->get();
        }
        
        //return products id if product is found
        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            
            return $row['products_id'];
        }
        
        return NULL;
    }

    /**
     * Get product data
     *
     * @access public
     * @param $id
     * @return array
     */
    public function get_data($id)
    {
        $data = FALSE;

        //get products & products description data
        $result = $this->db->select('p.products_id, p.products_model as model, p.products_sku as sku, p.products_type as type, p.products_quantity as quantity, p.products_max_order_quantity as max_order_quantity, p.products_moq as moq, p.order_increment as order_increment, p.products_price as price, p.products_tax_class_id as tax_class_id, p.products_date_added as date_added, p.products_date_available as date_available, p.manufacturers_id, p.quantity_discount_groups_id, p.quantity_unit_class, p.quantity_discount_groups_id as quantity_discount_groups_id, p.products_weight as products_weight, p.products_weight_class as products_weight_class, pd.products_name as name, pd.products_short_description as short_description, pd.products_description as description, pd.products_page_title as page_title, pd.products_meta_keywords as meta_keywords, pd.products_meta_description as meta_description, pd.products_keyword as keyword, pd.products_tags as tags, pd.products_url as url, quc.quantity_unit_class_title as unit_class, m.manufacturers_name, i.image as default_image, f.products_id as featured_products_id, s.specials_new_products_price as specials_price')
            ->from('products as p')
            ->join('products_description as pd', 'p.products_id = pd.products_id', 'inner')
            ->join('manufacturers as m', 'p.manufacturers_id = m.manufacturers_id', 'left')
            ->join('products_images as i', 'p.products_id = i.products_id', 'left')
            ->join('products_frontpage as f', 'p.products_id = f.products_id', 'left')
            ->join('quantity_unit_classes as quc', 'p.quantity_unit_class = quc.quantity_unit_class_id', 'left')
            ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
            ->where('p.products_id', $id)
            ->where('p.products_status', '1')
            ->where('i.default_flag', '1')
            ->where('pd.language_id', lang_id())
            ->where('quc.language_id = pd.language_id')
            ->get();
            
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
            $result->free_result();

            //get products images data
            $result = $this->db->select('id, image, default_flag')->from('products_images')->where('products_id', $id)->order_by('sort_order')->get();
            if ($result->num_rows() > 0)
            {
                $data['images'] = $result->result_array();
                $result->free_result();
            }

            //get products categories
            $result = $this->db->select('categories_id')->from('products_to_categories')->where('products_id', $id)->limit(1)->get();
            if ($result->num_rows() > 0)
            {
                $row = $result->row_array();
                $data['categories_id'] = $row['categories_id'];
                $result->free_result();
            }

            //products attachments
            $result = $this->db->select('attachments_name, pa.attachments_id, filename, cache_filename, attachments_description')
            ->from('products_attachments as pa')
            ->join('products_attachments_description as pad', 'pa.attachments_id = pad.attachments_id', 'inner')
            ->join('products_attachments_to_products as pa2p', 'pa2p.attachments_id = pa.attachments_id', 'inner')
            ->where('pa2p.products_id', $id)
            ->where('pad.languages_id', lang_id())
            ->get();

            if ($result->num_rows() > 0)
            {
                $data['attachments'] = $result->result_array();
                $result->free_result();
            }

            //products accessories
            $data['accessories'] = NULL;
            $result = $this->db->select('accessories_id')->from('products_accessories')->where('products_id', $id)->get();
            if ($result->num_rows() > 0)
            {
                $data['accessories'] = $result->result_array();
                $result->free_result();
            }

            //products accessories
            $result = $this->db->select('pav.name, pav.module, pav.value as selections, pa.value')
            ->from('products_attributes as pa')
            ->join('products_attributes_values as pav', 'pa.products_attributes_values_id = pav.products_attributes_values_id', 'inner')
            ->where('pa.language_id = pav.language_id')
            ->where('pa.products_id', $id)
            ->where('pa.language_id', lang_id())
            ->get();

            if ($result->num_rows() > 0)
            {
                $attributes = array();
                foreach($result->result_array() as $row)
                {
                    if ($row['module'] == 'pull_down_menu')
                    {
                        $selections = $row['selections'];
                        $selections = explode(',', $selections);

                        if (isset($selections[$value - 1])) {
                            $value = $selections[$value - 1];
                        }
                    }
                    $attributes[] = array('name' => $name, 'value' => $value);
                }
                $data['accessories'] = $attributes;

                $result->free_result();
            }

            //products variants
            $products_variants = array();
            $res_variants = $this->db->select('*')->from('products_variants')->where('products_id', $id)->order_by('is_default', 'desc')->get();

            $groups = array();
            $values = array();
            $groups_values = array();
            foreach($res_variants->result_array() as $variant)
            {
                $res_values = $this->db->select('pve.products_variants_groups_id as groups_id, pve.products_variants_values_id as variants_values_id, pvg.products_variants_groups_name as groups_name, pvv.products_variants_values_name as variants_values_name')
                ->from('products_variants_entries as pve')
                ->join('products_variants_groups as pvg', 'pve.products_variants_groups_id = pvg.products_variants_groups_id', 'inner')
                ->join('products_variants_values as pvv', 'pve.products_variants_values_id = pvv.products_variants_values_id', 'inner')
                ->where('pvg.language_id = ' . lang_id())
                ->where('pvv.language_id = ' . lang_id())
                ->where('pve.products_variants_id = ' . $variant['products_variants_id'])
                ->order_by('pve.products_variants_groups_id')
                ->get();

                $variants = array();
                $groups_name = array();
                foreach($res_values->result_array() as $value)
                {
                    $variants[$value['groups_id']] = $value['variants_values_id'];
                    $groups_name[$value['groups_name']] = $value['variants_values_name'];

                    $groups[$value['groups_id']] = $value['groups_name'];
                    $values[$value['variants_values_id']] = $value['variants_values_name'];

                    if (!isset($groups_values[$value['groups_id']]) || !is_array($groups_values[$value['groups_id']]))
                    {
                        $groups_values[$value['groups_id']] = array();
                    }

                    if (!in_array($value['variants_values_id'], $groups_values[$value['groups_id']]))
                    {
                        $groups_values[$value['groups_id']][] = $value['variants_values_id'];
                    }
                }

                $product_id_string = get_product_id_string($id, $variants);

                $products_variants[$product_id_string]['variants_id'] = $variant['products_variants_id'];
                $products_variants[$product_id_string]['is_default'] = $variant['is_default'];
                $products_variants[$product_id_string]['sku'] = $variant['products_sku'];
                $products_variants[$product_id_string]['price'] = $variant['products_price'];
                $products_variants[$product_id_string]['status'] = $variant['products_status'];
                $products_variants[$product_id_string]['weight'] = $variant['products_weight'];
                $products_variants[$product_id_string]['groups_id'] = $variants;
                $products_variants[$product_id_string]['groups_name'] = $groups_name;
                $products_variants[$product_id_string]['filename'] = $variant['filename'];
                $products_variants[$product_id_string]['cache_filename'] = $variant['cache_filename'];

                //get variant image through id
                foreach ($data['images'] as $image)
                {
                    if ($image['id'] == $variant['products_images_id']) {
                        $products_variants[$product_id_string]['image'] = $image['image'];
                    }
                }

                if ($variant['is_default'] == 1)
                {
                    $data['default_variant'] = $products_variants[$product_id_string];
                    $data['default_variant']['product_id_string'] = $product_id_string;
                }
            }

            $data['variants'] = $products_variants;
            $data['variants_groups'] = $groups;
            $data['variants_values'] = $values;
            $data['variants_groups_values'] = $groups_values;

            //average reviews rating
            $data['rating'] = $this->get_average_reviews_rating($data['products_id']);
        }

        return $data;
    }

    /**
     * Get average reviews rating
     *
     * @access public
     * @param $products_id
     * @return int
     */
    public function get_average_reviews_rating($products_id)
    {
        $result = $this->db->select('avg(reviews_rating) as rating')->from('reviews')->where('products_id', (int)$products_id)->where('languages_id', (int)lang_id())->where('reviews_status', 1)->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return round($row['rating']);
        }

        return FALSE;
    }

    /**
     * Get quantity
     *
     * @access public
     * @param $products_id
     * @return int
     */
    public function get_product_quantity($products_id)
    {
        $result = $this->db->select('products_quantity')->from('products')->where('products_id', $products_id)->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row['quantity'];
        }

        return 0;
    }

    /**
     * Get product variant quantity
     *
     * @access public
     * @param $variant
     * @return int
     */
    public function get_product_variant_quantity($variant)
    {
        $result = $this->db->select('products_quantity as quantity')->from('products_variants')->where('products_variants_id', $variant['variants_id'])->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row['quantity'];
        }

        return 0;
    }


    /**
     * Get product variant quantity
     *
     * @access public
     * @param $variant
     * @return int
     */
    public function get_product_variant_group_and_value_name($products_id, $group_id, $value_id)
    {
        $result = $this->db->select('pvg.products_variants_groups_name, pvv.products_variants_values_name')
            ->from('products_variants pv')
            ->from('products_variants_entries pve')
            ->from('products_variants_groups pvg')
            ->from('products_variants_values pvv')
            ->where('pv.products_id', $products_id)
            ->where('pv.products_variants_id = pve.products_variants_id')
            ->where('pve.products_variants_groups_id', $group_id)
            ->where('pve.products_variants_values_id', $value_id)
            ->where('pve.products_variants_groups_id = pvg.products_variants_groups_id')
            ->where('pve.products_variants_values_id = pvv.products_variants_values_id')
            ->where('pvg.language_id', lang_id())
            ->where('pvv.language_id', lang_id())->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row;
        }

        return FALSE;
    }

    /**
     * Get products according to the filter parameters
     *
     * @access public
     * @param $filter
     * @return array
     */
    public function get_products($filter = array())
    {
        $this->db->select('p.*, pd.*, m.*, i.image, s.specials_new_products_price as specials_price, f.products_id as featured_products_id')
                 ->from('products as p')
                 ->join('products_description as pd', 'p.products_id = pd.products_id and pd.language_id =' . lang_id(), 'inner')
                 ->join('products_to_categories as p2c', 'p.products_id = p2c.products_id', 'inner')
                 ->join('products_frontpage as f', 'p.products_id = f.products_id', 'left')
                 ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
                 ->join('categories as c', 'p2c.categories_id = c.categories_id', 'inner')
                 ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
                 ->join('manufacturers as m', 'p.manufacturers_id = m.manufacturers_id', 'left')
                 ->join('manufacturers_info as mi', 'm.manufacturers_id = mi.manufacturers_id and mi.languages_id = ' . lang_id(), 'left')
                 ->where('p.products_status', 1);

        //filter: categories_id
        if (isset($filter['categories_id']) && !empty($filter['categories_id']))
        {
            if (isset($filter['recursive']) && ($filter['recursive'] == TRUE))
            {
                $subcategories = array($filter['categories_id']);
                $this->category_tree->get_children($filter['categories_id'], $subcategories);

                $this->db->where_in('p2c.categories_id', $subcategories);
            }
            else
            {
                $this->db->where('p2c.categories_id', $filter['categories_id']);
            }
        }

        //filter: manufacturer
        if (isset($filter['manufacturer']) && !empty($filter['manufacturer']))
        {
            $this->db->where('m.manufacturers_id', $filter['manufacturer']);
        }
        
        //sort: name, price, sku, rating
        $order_by = 'pd.products_name';
        if (isset($filter['sort'])) {
            $entries = array('name', 'price', 'model');
            
            list($entry, $sort) = explode('|', $filter['sort']);
            
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

        $result = $this->db->order_by($order_by)->limit($filter['per_page'], $filter['page'] * $filter['per_page'])->get();
        
        $products = array();
        if ($result->num_rows() > 0)
        {
            $products = $result->result_array();
        }

        return $products;
    }

    /**
     * Get products account according to the filter parameters
     *
     * @param $filter
     */
    public function count_products($filter = array())
    {
        $this->db->select('count(p.products_id) as total')
                 ->from('products as p')
                 ->join('products_description as pd', 'p.products_id = pd.products_id and pd.language_id =' . lang_id(), 'inner')
                 ->join('products_to_categories as p2c', 'p.products_id = p2c.products_id', 'inner')
                 ->join('products_frontpage as f', 'p.products_id = f.products_id', 'left')
                 ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
                 ->join('categories as c', 'p2c.categories_id = c.categories_id', 'inner')
                 ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
                 ->join('manufacturers as m', 'p.manufacturers_id = m.manufacturers_id', 'left')
                 ->join('manufacturers_info as mi', 'm.manufacturers_id = mi.manufacturers_id and mi.languages_id = ' . lang_id(), 'left')
                 ->where('p.products_status', 1);
            
        //filter: categories_id
        if (isset($filter['categories_id']) && !empty($filter['categories_id']))
        {
            if (isset($filter['recursive']) && ($filter['recursive'] == TRUE))
            {
                $subcategories = array($filter['categories_id']);
                $this->category_tree->get_children($filter['categories_id'], $subcategories);

                $this->db->where_in('p2c.categories_id', $subcategories);
            }
            else
            {
                $this->db->where('p2c.categories_id', $filter['categories_id']);
            }
        }

        //filter: manufacturer
        if (isset($filter['manufacturer']) && !empty($filter['manufacturer']))
        {
            $this->db->where('m.manufacturers_id', $filter['manufacturer']);
        }

        $result = $this->db->get();

        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            
            return $row['total'];
        }

        return 0;
    }

    public function get_product_data($products_id)
    {
        $query = $this->db
            ->select('*')
            ->from('products as p')
            ->join('manufacturers as m', 'p.manufacturers_id = m.manufacturers_id', 'left')
            ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
            ->join('products_description as pd', 'p.products_id = pd.products_id', 'inner')
            ->where('pd.language_id = ' . lang_id() . ' and p.products_id = ' . $products_id)
            ->get();

        $product = FALSE;
        if ($query->num_rows() > 0)
        {
            $product = $query->row_array();
        }

        return $product;
    }

    /**
     * Get new products
     *
     * @access public
     * @param $filter
     * @return array
     */
    public function get_new_products($filter = array())
    {
        $result = $this->db->select('p.products_id, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_keyword, pd.products_short_description, i.image, s.specials_new_products_price as specials_price, f.products_id as featured_products_id')
            ->from('products p')
            ->join('products_description as pd', 'p.products_id = pd.products_id and pd.language_id =' . lang_id(), 'inner')
            ->join('products_frontpage as f', 'p.products_id = f.products_id', 'left')
            ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
            ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
            ->where('p.products_status = 1')
            ->order_by('p.products_date_added desc')
            ->limit($filter['per_page'], $filter['page'] * $filter['per_page'])
            ->get();

        $products = array();
        if ($result->num_rows() > 0)
        {
            $products = $result->result_array();
        }

        return $products;
    }

    /**
     * Get new products count
     *
     * @access public     *
     * @return int
     */
    public function count_new_products()
    {
        $result = $this->db->select('count(p.products_id) as total')
            ->from('products p')
            ->join('products_images i', 'p.products_id = i.products_id', 'left')
            ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
            ->where('p.products_status', 1)
            ->where('pd.language_id', lang_id())
            ->where('i.default_flag', 1)
            ->order_by('p.products_date_added', 'desc')
            ->get();

        $total = array();
        if ($result->num_rows() > 0)
        {
            $total = $result->row_array();
        }

        return $total['total'];
    }

    /**
     * Get feature products
     *
     * @access public
     * @param $category_id
     * @return mixed
     */
    public function get_feature_products($category_id = -1, $limit = NULL)
    {
        if ($category_id < 1) {
            $this->db->select('p.products_id, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_keyword, f.sort_order, i.image, pd.products_short_description, s.specials_new_products_price as specials_price, f.products_id as featured_products_id')
                     ->from('products p')
                     ->join('products_description as pd', 'p.products_id = pd.products_id and pd.language_id =' . lang_id(), 'inner')
                     ->join('products_frontpage as f', 'p.products_id = f.products_id', 'inner')
                     ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
                     ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
                     ->where('p.products_status = 1')
                     ->order_by('f.sort_order desc');
                
            if (is_numeric($limit)) {
                $this->db->limit($limit);
            }
            
            $result = $this->db->get();
        } else {
            $this->db->select('p.products_id, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_keyword, f.sort_order, i.image, pd.products_short_description, s.specials_new_products_price as specials_price, f.products_id as featured_products_id')
                ->from('products p')
                ->join('products_description as pd', 'p.products_id = pd.products_id and pd.language_id =' . lang_id(), 'inner')
                ->join('products_frontpage as f', 'p.products_id = f.products_id', 'inner')
                ->join('products_images as i', 'p.products_id = i.products_id and i.default_flag = 1', 'left')
                ->join('specials as s', 'p.products_id = s.products_id and s.status = 1 and s.start_date <= now() and s.expires_date >= now()', 'left')
                ->join('products_to_categories p2c', 'p2c.products_id = p.products_id', 'inner')
                ->join('categories c', 'c.categories_id = p2c.categories_id', 'inner')
                ->where('(c.parent_id = ' . $category_id . ' OR c.categories_id = ' . $category_id . ')')
                ->where('p.products_status = 1')
                ->order_by('f.sort_order desc');
                
            if (is_numeric($limit)) {
                $this->db->limit($limit);
            }
            
            $result = $this->db->get();
        }

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }

    /**
     * Get special products
     *
     * @access public
     * @param $filter
     * @return mixed
     */
    public function get_special_products($filter)
    {
        $result = $this->db->select('p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_short_description, pd.products_keyword,s.specials_new_products_price as specials_price, i.image, f.products_id as featured_products_id')
            ->from('products p')
            ->join('products_images i', 'p.products_id = i.products_id', 'left')
            ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
            ->join('specials s', 's.products_id = p.products_id', 'inner')
            ->join('products_frontpage as f', 'p.products_id = f.products_id', 'left')
            ->where('p.products_status = 1')
            ->where('pd.language_id', lang_id())
            ->where('i.default_flag', 1)
            ->where('s.status', 1)
            ->order_by('s.specials_date_added', 'desc')
            ->limit($filter['per_page'], $filter['page'])
            ->get();

        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }

    public function count_special_products()
    {
        $result = $this->db->select('count(p.products_id) as total')
            ->from('products p')
            ->join('products_images i', 'p.products_id = i.products_id', 'left')
            ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
            ->join('specials s', 's.products_id = p.products_id', 'inner')
            ->where('p.products_status = 1')
            ->where('pd.language_id', lang_id())
            ->where('i.default_flag', 1)
            ->where('s.status', 1)
            ->order_by('s.specials_date_added', 'desc')
            ->get();

        $total = array();
        if ($result->num_rows() > 0)
        {
            $total = $result->row_array();
        }

        return $total['total'];
    }

    public function get_review_count($id) {
        $result = $this->db->select('count(*) as reviews_count')
            ->from('reviews')
            ->where('products_id', $id)
            ->where('languages_id', lang_id())
            ->get();

        $count = array();
        if ($result->num_rows() > 0)
        {
            $count = $result->row_array();
        }

        return $count['reviews_count'];
    }

    /**
     * Get customers ratings
     *
     * @access public
     * @param $reviews_id
     * @return array
     */
    public function get_customers_ratings($reviews_id) {
        $result = $this->db->select('r.customers_ratings_id, r.ratings_id, r.ratings_value, rd.ratings_text')
            ->from('customers_ratings r')
            ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id', 'inner')
            ->where('rd.languages_id', lang_id())
            ->where('r.reviews_id', $reviews_id)->get();

        $ratings = array();
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row) {
                $ratings[] = array('value' => $row['ratings_value'], 'name' => $row['ratings_text']);
            }
        }

        return $ratings;
    }

    /**
     * Get product reviews listing
     *
     * @access public
     * @param $id
     * @return array
     */
    public function get_review_listing($id = null)
    {
        $result = $this->db->select('reviews_id, reviews_text, reviews_rating, date_added, customers_name')
            ->from('reviews')
            ->where('products_id', $id)
            ->where('languages_id', lang_id())
            ->where('reviews_status', 1)
            ->order_by('reviews_id', 'desc')->get();

        $reviews = array();
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row) {
                $ratings = $this->get_customers_ratings($row['reviews_id']);
                $row['ratings'] = $ratings;

                $reviews[] = $row;
            }
        }

        return $reviews;
    }

    /**
     * Increment product view counter
     *
     * @access public
     * @param $id
     * @return
     */
    public function increment_counter($id)
    {
        $this->db->update('products_description', array('products_viewed' => 'products_viewed+1'), array('products_id' => $id, 'language_id' => lang_id()));
    }

    /**
     * Get product category ratings
     *
     * @access public
     * @param $categories_id
     * @return array
     */
    function get_category_ratings($categories_id)
    {
        $result = $this->db->select('cr.ratings_id, rd.ratings_text')
            ->from('categories_ratings cr')
            ->join('ratings_description rd', 'cr.ratings_id = rd.ratings_id', 'inner')
            ->where('cr.categories_id', $categories_id)
            ->where('rd.languages_id', lang_id())->get();

        $ratings = array();
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $ratings[$row['ratings_id']] = $row['ratings_text'];
            }
        }

        return $ratings;
    }

    /**
     * Get product name
     *
     * @access public
     * @param $id
     * @return array
     */
    public function get_product_friendly_url($id)
    {
        //get products & products description data
        $result = $this->db->select('products_friendly_url')->from('products_description')->where('products_id', $id)->where('language_id', lang_id())->get();
        
        if ($result->num_rows() > 0) 
        {
            $row = $result->row_array();
            
            return $row['products_friendly_url'];
        }
        
        return NULL;
    }
}

/* End of file products_model.php */
/* Location: ./system/tomatocart/models/products_model.php */