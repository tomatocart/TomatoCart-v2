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
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Products_Model extends CI_Model 
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }  
    
// ------------------------------------------------------------------------

    /**
     * Get the products
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $in_categories
     * @param $search
     * @return mixed
     */
    public function get_products($start, $limit, $in_categories, $search)
    {
        $this->load->library('currencies');
         
        $this->db
        ->select('p.products_id, p.products_type, pd.products_name, p.products_quantity, p.products_price, p.products_quantity, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status')
        ->from('products p')
        ->join('products_description pd', 'p.products_id = pd.products_id', 'inner');
             
        if (!empty($in_categories))
        {
            $this->db
            ->join('products_to_categories p2c', 'p.products_id = p2c.products_id', 'inner')
            ->where_in('p2c.categories_id', $in_categories);
        }
        
        $this->db->where('pd.language_id', lang_id());
        
        if (!empty($search))
        {
            $this->db->like('pd.products_name', $search);
        }
        
        $this->db
        ->order_by('pd.products_id desc')
        ->limit($limit, $start);
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            $records = array();
            foreach($result->result_array() as $product)
            {
                $products_price = $this->currencies->format($product['products_price']);
                
                if ($product['products_type'] == PRODUCT_TYPE_GIFT_CERTIFICATE)
                {
                    $result_certificate = $this->db
                    ->select('open_amount_min_value, open_amount_max_value')
                    ->from('products_gift_certificates')
                    ->where(array('gift_certificates_amount_type' => GIFT_CERTIFICATE_TYPE_OPEN_AMOUNT, 'products_id' => $product['products_id']))
                    ->get();
                    
                    if ($result_certificate->num_rows() > 0)
                    {
                        $products_price = $this->currencies->format($result_certificate->value('open_amount_min_value')) . ' ~ ' . $this->currencies->format($result_certificate->value('open_amount_max_value'));
                    }
                }
                
                $result_status = $this->db
                ->select('products_id')
                ->from('products_frontpage')
                ->where('products_id', $product['products_id'])
                ->get();
                
                if ($result_status->num_rows() > 0)
                {
                    $products_frontpage = 1;
                }
                else
                {
                    $products_frontpage = 0;
                }
                
                $records[] = array(
                  'products_id'         => $product['products_id'],
                  'products_name'       => $product['products_name'],
                  'products_frontpage'  => $products_frontpage,
                  'products_status'     => $product['products_status'],
                  'products_price'      => $products_price,
                  'products_quantity'   => $product['products_quantity']
                );
            }
            
            return $records;    
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the manufacturers
     *
     * @access public
     * @return array
     */
    public function get_manufacturers()
    {
        $result = $this->db
        ->select('manufacturers_id, manufacturers_name')
        ->from('manufacturers')
        ->order_by('manufacturers_name')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the weight classes
     *
     * @access public
     * @return mixed
     */
    public function get_weight_classes()
    {
        $result = $this->db
        ->select('weight_class_id, weight_class_title')
        ->from('weight_classes')
        ->where('language_id', lang_id())
        ->order_by('weight_class_title')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the tax classes
     *
     * @access public
     * @return mixed
     */
    public function get_tax_classes()
    {
        $result = $this->db
        ->select('tax_class_id, tax_class_title')
        ->from('tax_class')
        ->order_by('tax_class_title')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the quantity discount groups
     *
     * @access public
     * @return mixed
     */
    public function get_quantity_discount_groups()
    {
        $result = $this->db
        ->select('quantity_discount_groups_id, quantity_discount_groups_name')
        ->from('quantity_discount_groups')
        ->order_by('quantity_discount_groups_id')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the quantity units
     *
     * @access public
     * @return mixed
     */
    public function get_quantity_units()
    {
        $result = $this->db
        ->select('quantity_unit_class_id, quantity_unit_class_title')
        ->from('quantity_unit_classes')
        ->where('language_id', lang_id())
        ->order_by('quantity_unit_class_title')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get xsell products linked to the product
     *
     * @access public
     * @return mixed
     */
    public function get_xsell_products($products_id)
    {
        $result = $this->db
        ->select('pd.products_id, pd.products_name')
        ->from('products_xsell px')
        ->join('products_description pd', 'px.xsell_products_id = pd.products_id', 'inner')
        ->where(array('px.products_id' => $products_id, 'pd.language_id' => lang_id()))
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the xsell products
     *
     * @access public
     * @param $products_id
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_products_for_xsell($products_id, $start, $limit)
    {
        $this->db
        ->select('p.products_id, pd.products_name, p.products_quantity, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status')
        ->from('products p')
        ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
        ->where('pd.language_id', lang_id());
        
        if (is_numeric($products_id) && $products_id > 0)
        {
            $this->db->where('p.products_id !=', $products_id);
        }
        
        $result = $this->db->limit($limit, $start)->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the accessories
     *
     * @access public
     * @param $products_id
     * @return mixed
     */
    public function get_accessories($products_id) 
    {
        if (!empty($products_id))
        {
            $result = $this->db
            ->select('pd.products_id, pd.products_name')
            ->from('products_accessories pa')
            ->join('products_description pd', 'pa.accessories_id = pd.products_id', 'inner')
            ->where(array('pa.products_id' => $products_id, 'pd.language_id' => lang_id()))
            ->get();
          
            if ($result->num_rows() > 0)
            {
                return $result->result_array();
            }
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the product images
     * 
     * @access public
     * @param $products_id
     * @return mixed
     */
    public function get_images($products_id)
    {
        $result = $this->db
        ->select('id, image, default_flag')
        ->from('products_images')
        ->where('products_id', $products_id)
        ->order_by('sort_order')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Load the variants groups
     * 
     * @access public
     * @return mixed
     */
    public function load_variants_groups()
    {
        $result = $this->db
        ->select('products_variants_groups_id as groups_id, products_variants_groups_name as groups_name')
        ->from('products_variants_groups')
        ->where('language_id', lang_id())
        ->order_by('products_variants_groups_name')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get variants values
     * 
     * @access public
     * @param $group_id
     * @return mixed
     */
    public function get_variants_values($group_id)
    {
        $result = $this->db
        ->select('pvv.products_variants_values_id as variants_id, pvv.products_variants_values_name as variants_name')
        ->from('products_variants_values pvv')
        ->join('products_variants_values_to_products_variants_groups pv2pv', 'pvv.products_variants_values_id = pv2pv.products_variants_values_id', 'inner')
        ->where(array('pv2pv.products_variants_groups_id' => $group_id, 'pvv.language_id' => lang_id()))
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the product
     * 
     * @access public
     * @param $id
     * @param $data
     * @return mixed
     */
    public function save($id = NULL, $data)
    {
        $this->load->helper('file');
        $this->load->library('image');
        
        $image_path = ROOTPATH . 'images/products/_upload/' . $this->session->userdata('session_id') . '/';
          
        $config = array('directory' => $image_path, 'stats' => TRUE);
        $this->load->library('directory_listing', $config);
        $this->directory_listing->set_include_directories(FALSE);
        
        $this->db->trans_begin();
        
        //products
        $products_data = array('products_type' => $data['products_type'], 
                               'products_sku' => $data['products_sku'], 
                               'products_model' => $data['products_model'], 
                               'products_price' => $data['price'], 
                               'products_quantity' => $data['quantity'], 
                               'products_moq' => $data['products_moq'], 
                               'products_max_order_quantity' => $data['products_max_order_quantity'], 
                               'order_increment' => $data['order_increment'], 
                               'products_weight' => $data['weight'], 
                               'products_weight_class' => $data['weight_class'], 
                               'products_status' => $data['status'], 
                               'products_tax_class_id' => $data['tax_class_id'], 
                               'manufacturers_id' => $data['manufacturers_id'], 
                               'quantity_discount_groups_id' => $data['quantity_discount_groups_id'],
                               'quantity_unit_class' => $data['quantity_unit_class'],
                               'products_last_modified' => date('Y-m-d'));
        
        if (date('Y-m-d') < $data['date_available'])
        {
            $products_data['products_date_available'] = $data['date_available'];
        }
        else
        {
            $products_data['products_date_available'] = NULL;
        }
        
        if (is_numeric($id))
        {
            $products_data['products_last_modified'] = date('Y-m-d');
            
            $this->db->where('products_id', $id)->update('products', $products_data);
        }
        else
        {
            $products_data['products_date_added'] = date('Y-m-d');
            
            $this->db->insert('products', $products_data);
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            if (is_numeric($id))
            {
                $products_id = $id;
            }
            else
            {
                $products_id = $this->db->insert_id();
            }
            
            //products_to_categories
            $this->db->delete('products_to_categories', array('products_id' => $products_id));
            
            if (isset($data['categories']) && !empty($data['categories']))
            {
                foreach ($data['categories'] as $category_id) 
                {
                    $products_to_categories = array('products_id' => $products_id, 'categories_id' => $category_id);
                    
                    $this->db->insert('products_to_categories', $products_to_categories);
                }
            }
        }
        
        //products_accessories
        if ($this->db->trans_status() === TRUE)
        {
            if (is_numeric($id))
            {
                echo 'delete';
                $this->db->delete('products_accessories', array('products_id' => $products_id));
            }
            
            if (isset($data['accessories_ids']) && sizeof($data['accessories_ids']) > 0)
            {
                foreach ($data['accessories_ids'] as $accessories_id)
                {
                    $accessory_data = array('products_id' => $products_id, 'accessories_id' => $accessories_id);
                    
                    $this->db->insert('products_accessories', $accessory_data);
                }
            }
        }
        
        //products_description
        if ($this->db->trans_status() === TRUE)
        {
            foreach(lang_get_all() as $l)
            {
                $products_description_data = array('products_name' => $data['products_name'][$l['id']], 
                                                   'products_short_description' => $data['products_short_description'][$l['id']], 
                                                   'products_description' => $data['products_description'][$l['id']], 
                                                   'products_tags' => $data['products_tags'][$l['id']], 
                                                   'products_url' => $data['products_url'][$l['id']], 
                                                   'products_friendly_url' => $data['products_friendly_url'][$l['id']], 
                                                   'products_page_title' => $data['products_page_title'][$l['id']], 
                                                   'products_meta_keywords' => $data['products_meta_keywords'][$l['id']], 
                                                   'products_meta_description' => $data['products_meta_description'][$l['id']]);
                                                   
                
                if (is_numeric($id))
                {
                    $this->db->update('products_description', $products_description_data, array('products_id' => $products_id, 'language_id' => $l['id']));
                }
                else
                {
                    $products_description_data['language_id'] = $l['id'];
                    $products_description_data['products_id'] = $products_id;
                    
                    $this->db->insert('products_description', $products_description_data);
                }
            }
        }
        
        //BEGIN: products images
        if ($this->db->trans_status() === TRUE)
        {
            $images = array();
            
            foreach($this->directory_listing->get_files() as $file)
            {
                @copy($image_path . $file['name'], ROOTPATH . 'images/products/originals/' . $file['name']);
                @unlink($image_path . $file['name']);
                
                $images[$file['name']] = -1;
            }
            
            delete_files($image_path);
            
            $default_flag = 1;
            
            $images_keys = array_keys($images);
            foreach ($images_keys as $image)
            {
                $image_data = array('products_id' => $products_id, 
                                    'default_flag' => $default_flag, 
                                    'sort_order' => 0,
                                    'image' => '', 
                                    'date_added' => date('Y-m-d'));
                
                $this->db->insert('products_images', $image_data);
                
                $image_id = $this->db->insert_id();
                
                $images[$image] = $image_id;
                
                $new_image_name =  $products_id . '_' . $image_id . '_' . $image;
                @rename(ROOTPATH . 'images/products/originals/' . $image, ROOTPATH . 'images/products/originals/' . $new_image_name);
                
                $this->db->update('products_images', array('image' => $new_image_name), array('id' => $image_id));
                
                foreach ($this->image->get_groups() as $group) 
                {
                    if ($group['id'] != '1')
                    {
                        $this->image->resize($new_image_name, $group['id'], 'products');
                    }
                }
                
                $default_flag = 0;
            }
        }
        //END: products images
        
        //BEGIN: products variants
        if ($this->db->trans_status() === TRUE)
        {
            //if edit product, delete variant first
            if (is_numeric($id))
            {
                $result = $this->db
                ->select('*')
                ->from('products_variants')
                ->where('products_id', $id)
                ->order_by('products_variants_id')
                ->get();
                
                $records = array();
                if ($result->num_rows() > 0)
                {
                    foreach($result->result_array() as $product_variant)
                    {
                        $result_entries = $this->db
                        ->select('products_variants_id, products_variants_groups_id, products_variants_values_id')
                        ->from('products_variants_entries')
                        ->where('products_variants_id', $product_variant['products_variants_id'])
                        ->order_by('products_variants_groups_id', 'products_variants_values_id')
                        ->get();
                        
                        $variants_values = array();
                        if ($result_entries->num_rows() > 0)
                        {
                            foreach($result_entries->result_array() as $entry)
                            {
                                $variants_values[] = $entry['products_variants_groups_id'] . '_' . $entry['products_variants_values_id'];
                            }
                        }
                        
                        $result_entries->free_result();
            
                        $variant = implode('-', $variants_values);
                        
                        if (!isset($data['products_variants_id'][$variant]))
                        {
                            //delete variants
                            $this->db->delete('products_variants', array('products_variants_id' => $product_variant['products_variants_id']));
                            
                            //delete variants entries
                            if ($this->db->trans_status() === TRUE)
                            {
                                $this->db->delete('products_variants_entries', array('products_variants_id' => $product_variant['products_variants_id']));
                            }
                        }
                    }
                }
                
                $result->free_result();
            }
            
            $products_quantity = 0;
            
            //insert or update variant
            if (isset($data['products_variants_id']) && is_array($data['products_variants_id']))
            {
                foreach($data['products_variants_id'] as $key => $variants_id)
                {
                    $product_variants_data = array('is_default' => $data['variants_default'][$key], 
                                                   'products_price' => $data['variants_price'][$key], 
                                                   'products_sku' => $data['variants_sku'][$key], 
                                                   'products_model' => $data['variants_model'][$key], 
                                                   'products_quantity' => $data['variants_quantity'][$key], 
                                                   'products_weight' => $data['variants_weight'][$key], 
                                                   'products_status' => $data['variants_status'][$key], 
                                                   'filename' => '', 
                                                   'cache_filename' => '',
                                                   'products_images_id' => '');
                    
                    $products_images_id = '';
                    if (is_numeric($data['variants_image'][$key])) {
                        $products_images_id = $data['variants_image'][$key];
                    } else if (isset($images[$data['variants_image'][$key]])) {
                        $products_images_id = $images[$data['variants_image'][$key]];
                    }
                    $product_variants_data['products_images_id'] = $products_images_id;
                    
                    if ($variants_id > 0)
                    {
                        $this->db->update('products_variants', $product_variants_data, array('products_variants_id' => $variants_id));
                    }
                    else
                    {
                        $product_variants_data['products_id'] = $products_id;
                        
                        $this->db->insert('products_variants', $product_variants_data);
                    }
                    
                    if ($this->db->trans_status() === FALSE)
                    {
                        break;
                    }
                    else
                    {
                        if ( is_numeric($variants_id) && ($variants_id > 0) ) {
                            $products_variants_id = $variants_id;
                        } 
                        else 
                        {
                            $products_variants_id = $this->db->insert_id();
                        }
                        
                        $products_quantity += $data['variants_quantity'][$key];
                    }
                    
                    //variant entries
                    if ( ($this->db->trans_status() === TRUE) && ($variants_id == '-1') )
                    {
                        $assigned_variants = explode('-', $key);
                        
                        for ($i = 0; $i < sizeof($assigned_variants); $i++)
                        {
                            $assigned_variant = explode('_', $assigned_variants[$i]);
                            
                            $entries_data = array('products_variants_id' => $products_variants_id, 
                                                  'products_variants_groups_id' => $assigned_variant[0], 
                                                  'products_variants_values_id' => $assigned_variant[1]);
                            
                            $this->db->insert('products_variants_entries', $entries_data);
                            
                            if ($this->db->trans_status() === FALSE)
                            {
                                break;
                            }
                        }
                    }
                }
                
                if ($this->db->trans_status() === TRUE)
                {
                  $this->db->update('products', array('products_quantity' => $products_quantity), array('products_id' => $products_id));
                }
            }
        }
        //END: products variants
        
        //BEGIN: xsell products
        if ($this->db->trans_status() === TRUE)
        {
            if (is_numeric($id))
            {
                $this->db->delete('products_xsell', array('products_id' => $id));
            }
            
            if ($this->db->trans_status() === TRUE)
            {
                if (isset($data['xsell_id_array']) && !empty($data['xsell_id_array']))
                {
                    foreach ($data['xsell_id_array'] as $xsell_products_id)
                    {
                        $this->db->insert('products_xsell', array('products_id' => $products_id, 'xsell_products_id' => $xsell_products_id));
                        
                        if ($this->db->trans_status() === FALSE)
                        {
                           break;
                        }
                    }
                }
            }
        }
        //END: xsell products
        
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();
            
            return $products_id;
        }
        else
        {
            $this->db->trans_rollback();
            
            return FALSE;
        }
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the data of the product
     * 
     * @access public
     * @param $products_id
     * @return mixed
     */
    public function get_data($products_id)
    {
        $result = $this->db
        ->select('p.*, pd.*, ptoc.*')
        ->from('products p')
        ->join('products_description pd', 'p.products_id = pd.products_id', 'left')
        ->join('products_to_categories ptoc', 'ptoc.products_id = p.products_id', 'left')
        ->where(array('p.products_id' => $products_id, 'pd.language_id' => lang_id()))
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Load the product description data
     * 
     * @access public
     * @param $products_id
     * @param $data
     * @return mixed
     */
    public function load_product_data($products_id, $data = array())
    {
        $result = $this->db
        ->select('*')
        ->from('products_description')
        ->where('products_id', $products_id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            foreach($result->result_array() as $product_description)
            {
                $language_id = $product_description['language_id'];
                
                $data['products_name[' . $language_id . ']'] = $product_description['products_name'];
                $data['products_short_description[' . $language_id . ']'] = $product_description['products_short_description'];
                $data['products_description[' . $language_id . ']'] = $product_description['products_description'];
                $data['products_tags[' .$language_id . ']'] = $product_description['products_tags'];
                $data['products_url[' . $language_id . ']'] = $product_description['products_url'];
                $data['products_friendly_url[' . $language_id . ']'] = $product_description['products_friendly_url'];
                $data['products_page_title[' . $language_id . ']'] = $product_description['products_page_title'];
                $data['products_meta_keywords[' . $language_id . ']'] = $product_description['products_meta_keywords'];
                $data['products_meta_description[' . $language_id . ']'] = $product_description['products_meta_description'];
            }
            
            return $data;
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the product
     * 
     * @access public
     * @param $product_id
     * @return boolean
     */
    public function delete_product($product_id)
    {
        $this->load->library('image');
        
        $this->db->trans_begin();
        
         //reviews
        $this->db->delete('reviews', array('products_id' => $product_id));
        
        //customers basket
        if ($this->db->trans_status() === TRUE)
        {
            $this->db
            ->where('products_id', $product_id)
            ->or_like('products_id', $product_id)
            ->delete('customers_basket');
        }
        
        //categories
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('products_to_categories', array('products_id' => $product_id));
        }
        
        //xsell
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('products_xsell', array('products_id' => $product_id));
        }
        
       //variants entries
        if ($this->db->trans_status() === TRUE)
        {
            $tbl_pve = $this->db->protect_identifiers('products_variants_entries', TRUE);
            $tbl_pv = $this->db->protect_identifiers('products_variants', TRUE);
            
            $sql = 'delete from ' . $tbl_pve . 'where products_variants_id in (select products_variants_id from ' . $tbl_pv . ' where products_id = ?)';
            $this->db->query($sql, array((int)$product_id));
        }
        
        //variants
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('products_variants', array('products_id' => $product_id));
        }
       
        //products description
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('products_description', array('products_id' => $product_id));
        }
        
        //product accessories
        if ($this->db->trans_status() === TRUE)
        {
           $this->db->delete('products_accessories', array('products_id' => $product_id));
        }
        
        //products
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('products', array('products_id' => $product_id));
        }
        
        //images
        if ($this->db->trans_status() === TRUE)
        {
            $result = $this->db
            ->select('id')
            ->from('products_images')
            ->where('products_id', $product_id)
            ->get();
            
            if ($result->num_rows() > 0)
            {
                foreach($result->result_array() as $image)
                {
                    $this->image->delete($image['id']);
                }
            }
        }
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            
            return FALSE;
        }
        else
        {
            $this->db->trans_commit();
            
            return TRUE;
        }
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the categories that the product belong to
     *
     * @access public
     * @param $products_id
     * @return mixed
     */
    public function get_products_to_categories($products_id)
    {
        $result = $this->db
        ->select('categories_id')
        ->from('products_to_categories')
        ->where('products_id', $products_id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the product variants
     *
     * @access public
     * @param $products_id
     * @return mixed
     */
    public function get_products_variants($products_id)
    {
        $result = $this->db
        ->select('*')
        ->from('products_variants')
        ->where('products_id', $products_id)
        ->order_by('products_variants_id')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
     /**
     * Get the variants entries
     *
     * @access public
     * @param $products_varaints_id
     * @return mixed
     */
    public function get_variants_entries($products_varaints_id)
    {
        $result = $this->db
        ->select('e.products_variants_id, e.products_variants_groups_id as gid, e.products_variants_values_id as vid, g.products_variants_groups_name as gname, v.products_variants_values_name as vname')
        ->from('products_variants_entries e')
        ->join('products_variants_groups g', 'e.products_variants_groups_id = g.products_variants_groups_id', 'inner')
        ->join('products_variants_values v', 'e.products_variants_values_id = v.products_variants_values_id', 'inner')
        ->where('g.language_id = v.language_id')
        ->where(array('g.language_id' => lang_id(), 'e.products_variants_id' => $products_varaints_id))
        ->order_by('g.products_variants_groups_id, v.products_variants_values_id')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of the products
     *
     * @access public
     * @param $search
     * @param $in_categories
     * @return int
     */
    public function get_totals($search = NULL, $in_categories = NULL)
    {
        if ($search === NULL && $in_categories === NULL)
        {
            return $this->db->count_all('products');
        }
        else
        {
            $this->db
            ->select('p.products_id, p.products_type, pd.products_name, p.products_quantity, p.products_price, p.products_quantity, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status')
            ->from('products p')
            ->join('products_description pd', 'p.products_id = pd.products_id', 'inner');
                 
            if (count($in_categories) > 0)
            {
                $this->db
                ->join('products_to_categories p2c', 'p.products_id = p2c.products_id', 'inner')
                ->where_in('p2c.categories_id', $in_categories);
            }
            
            $this->db->where('pd.language_id', lang_id());
            
            if ($search !== NULL)
            {
                $this->db->like('pd.products_name', $search);
            }
            
            $result = $this->db->get();
            
            return $result->num_rows();
        }
    }
    
// ------------------------------------------------------------------------

    /**
     * Set the frontpage product
     *
     * @access public
     * @param $id
     * @param $flag
     * @return boolean
     */
    public function set_frontpage($id, $flag)
    {
        if ($flag == 1)
        {
            $result = $this->db
            ->select('products_id')
            ->from('products_frontpage')
            ->where('products_id', $id)
            ->get();
            
            if ($result->num_rows() > 0)
            {
                return TRUE;
            }
            
            $result->free_result();
            
            $result = $this->db
            ->select_max('sort_order')
            ->from('products_frontpage')
            ->get();
            
            $max = $result->row_array();
            
            $sort_order =  $max['sort_order'] + 1;
            
            $this->db->insert('products_frontpage', array('products_id' => $id, 'sort_order' => $sort_order));
        }
        else
        {
            $this->db->delete('products_frontpage', array('products_id' => $id));
        }
        
        if ($this->db->affected_rows() == 1)
        {
            return TRUE;
        }
        
        return FALSE;
    }

// ------------------------------------------------------------------------
    
    /**
     * Set the status of the product
     *
     * @access public
     * @param $id
     * @param $flag
     * @return boolean
     */
    public function set_status($id, $flag)
    {
        $error = FALSE;
        
        if ($flag == 0)
        {
            $this->db->like('products_id', (int)$id . '#', 'after')->or_where('products_id', $id);
            $this->db->delete('customers_basket');
        }
        
        $this->db->update('products', array('products_status' => $flag), array('products_id' => $id));
        
        if ($this->db->affected_rows() < 1)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Upload the product image
     * 
     * @access public
     * @param $products_id
     * @param $image
     * @return boolean
     */
    public function do_edit_upload($products_id, $image)
    {
        $this->load->library('image');
        
        $default_flag = 1;
        
        $result = $this->db
        ->select('id')
        ->from('products_images')
        ->where(array('products_id' => $products_id, 'default_flag' => $default_flag))
        ->limit(1)
        ->get();
        
        if ($result->num_rows() === 1)
        {
            $default_flag = 0;
        }
        
        $result->free_result();
        
        $this->db->insert('products_images', array('products_id' => $products_id, 
                                                   'image' => $image, 
                                                   'default_flag' => $default_flag, 
                                                   'sort_order' => 0, 
                                                   'date_added' => date('Y-m-d H:i:s')));
        
        if ($this->db->affected_rows() > 0)
        {
            $image_path = ROOTPATH . 'images/products/originals/';
            $image_id = $this->db->insert_id();
            $new_image_name =  $products_id . '_' . $image_id . '_' . $image;
            @rename($image_path . $image, $image_path . $new_image_name);
            
            $this->db->update('products_images', array('image' => $new_image_name), array('id' => $image_id));
            
            if ($this->db->affected_rows() > 0)
            {
                foreach ($this->image->get_groups() as $group) 
                {
                    if ($group['id'] != '1')
                    {
                        $this->image->resize($new_image_name, $group['id'], 'products');
                    }
                }
                
                return TRUE;
            }
        }
        
        return FALSE;
        
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Restock
     * 
     * @access public
     * @param $orders_id
     * @param $orders_products_id
     * @param $products_id
     * @param $products_quantity
     * @return boolean
     */
    public function restock($orders_id, $orders_products_id, $products_id, $products_quantity)
    {
        if (STOCK_LIMITED == '1')
        {
            $this->db->trans_begin();
            
            $result = $this->db
            ->select('products_quantity, products_ordered')
            ->from('products')
            ->where('products_id', $products_id)
            ->get();
            
            if ($result->num_rows() > 0)
            {
                $row = $result->row_array();
            }
            $result->free_result();
            
            $this->db->update('products', 
                              array('products_quantity' => $row['products_quantity'] + $products_quantity, 
                                    'products_ordered' => $row['products_ordered'] - $products_quantity), 
                              array('products_id' => $products_id));
                              
            if ($this->db->trans_status() ===TRUE)
            {
                $result = $this->db
                ->select('products_quantity')
                ->from('products')
                ->where(array('products_id' => $products_id, 'products_status' => 0))
                ->get();
                
                if (($result->num_rows() === 1) && ($products_quantity > 0))
                {
                    $this->db->update('products', array('products_status' => 1), array('products_id' => $products_id));
                }
                
                $result->free_result();
            }
            
            if ($this->db->trans_status() ===TRUE)
            {
                $result = $this->db
                ->select('products_variants_groups_id, products_variants_values_id')
                ->from('orders_products_variants')
                ->where(array('orders_id' => $orders_id, 'orders_products_id' => $orders_products_id))
                ->get();
                
                if ($result->num_rows() > 0)
                {
                    $orders_variants = $result->result_array();
                    $variants = array();
                    
                    if (!empty($orders_variants))
                    {
                        foreach($orders_variants as $variant)
                        {
                            $variants[$variant['products_variants_groups_id']] = $variant['products_variants_values_id'];
                        }
                    }
                    $result->free_result();
                    
                    $products_variants_id = $this->product->get_product_variants_id($variants);
                    
                    $result = $this->db
                    ->select('products_quantity')
                    ->from('products_variants')
                    ->where(array('products_variants_id' => $products_variants_id))
                    ->get();
                    
                    $row = $result->row_array();
                    
                    $result->free_result();
                    
                    $this->db->update('products_variants', 
                                      array('products_quantity' => $row['products_quantity'] + $products_quantity), 
                                      array('products_variants_id' => $products_variants_id));
                                      
                    if ($this->db->trans_status() === TRUE)
                    {
                        $result = $this->db
                        ->select('products_quantity')
                        ->from('products_variants')
                        ->where(array('products_variants_id' => $products_variants_id, 'products_status' => 0))
                        ->get();
                        
                        if (($result->num_rows() === 1))
                        {
                            $row = $result->row_array();
                            if ($row['products_quantity'] > 0)
                            {
                                $this->db->update('products_variants', array('products_status' => 1), array('products_variants_id' => $products_variants_id));
                            }
                        }
                    }
                }
            }
            
            if ($this->db->trans_status() === TRUE)
            {
                $this->db->trans_commit();
                
                return TRUE;
            }
            else
            {
                $this->db->trans_rollback();
        
                return FALSE;
            }
        }
        else
        {
            return TRUE;
        }
    }
}

/* End of file products_model.php */
/* Location: ./system/models/products_model.php */