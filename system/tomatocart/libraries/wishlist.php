<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source wishlist Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Wishlist  Class
 *
 * The wishlist class is copied from TomatoCart v1.0. It keep some basic features and following features are
 * removed:
 *   gift wrapping
 *   gift wrapping message
 *   coupon
 *   gift certificate
 *   customer credit
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Wishlist
{
    /**
     * ci instance
     *
     * @access private
     * @var object
     */
    private $ci = NULL;

    /**
     * wishlist content
     *
     * @access private
     * @var object
     */
    private $contents = array();

    /**
     * wish list id
     *
     * @access private
     * @var object
     */
    private $wishlists_id = NULL;

    /**
     * token
     *
     * @access private
     * @var object
     */
    private $token = NULL;

    /**
     * customers id
     *
     * @access private
     * @var object
     */
    private $customers_id = NULL;

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        //get ci instance
        $this->ci = get_instance();
        $this->ci->load->model('wishlist_model');

        $content = $this->ci->session->userdata('wishlist_contents');
        if ($content === NULL) {
            $this->ci->session->set_userdata('wishlist_contents', array('contents' => array(),
                	                                                    'wishlists_id' => NULL, 
                	                                                    'token' => NULL));
        }

        $this->contents = $content['contents'];
        $this->wishlists_id = $content['wishlists_id'];
        $this->token = $content['token'];
    }

    /**
     * Check whether the product exist in the content
     *
     * @access public
     * @param $products_id
     * @return boolean
     */
    function exists($products_id)
    {
        return isset($this->contents[$products_id]);
    }

    /**
     * Has contents
     *
     * @access public
     * @return boolean
     */
    function has_contents()
    {
        return !empty($this->contents);
    }

    /**
     * Has wishlist id
     *
     * @access public
     * @return boolean
     */
    function has_wishlist_id()
    {
        return !empty($this->wishlists_id);
    }

    /**
     * Return token
     *
     * @access public
     * @return string
     */
    function get_token()
    {
        return $this->token;
    }

    /**
     * Reset wishlist
     *
     * @access public
     * @return void
     */
    function reset()
    {
        $this->contents = array();
        $this->wishlists_id = NULL;
        $this->token = NULL;
        
        $this->save_session();
    }

    /**
     * Generate token
     *
     * @access public
     * @return string
     */
    function generate_token()
    {
        $token = md5($this->customers_id  . time());

        return $token;
    }

    /**
     * add a product to wishlist
     *
     * @param int $products_id
     * @param string $comments
     * @return boolean
     */
    public function add_product($products_id, $variants = array())
    {
        //if wishlist empty, create a new wishlist
        if(!$this->has_wishlist_id())
        {
            //generate a new token for the wishlist
            $token = $this->generate_token();

            //create a wishlist in database
            $wishlists_id = $this->ci->wishlist_model->insert_wishlist($this->customers_id, $token);

            //assign local variable
            $this->wishlists_id = $wishlists_id;
            $this->token = $token;
        }

        //if the product does not exist in the wishlist
        if (!$this->exists($products_id)) {
            $product = load_product_library($products_id);

            //if the product is valid
            if ($product->is_valid())
            {
                $this->contents[$products_id] = array('products_id' => $products_id,
                                                      'name' => $product->get_title(),
                                                      'image' => $product->get_image(),
                                                      'price' => $product->get_price(), 
                									  'date_added' => get_date_now(),
                                                      'variants' => array(), //variants is not support 
                                                      'comments' => '');

                //insert product into database
                $this->ci->wishlist_model->add_product_to_wishlist($this->wishlists_id, $products_id);

                //save to session
                $this->save_session();

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Delete wishlist procuct
     *
     * @param int $products_id
     * @return boolean
     */
    public function delete_product($products_id)
    {
        if ($this->exists($products_id))
        {
            if($this->ci->wishlist_model->delete_product($this->wishlists_id, $products_id))
            {
                unset($this->contents[$products_id]);

                $this->save_session();

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Update wishlist
     *
     * @access public
     * @param $comments
     */
    function update_wishlist($comments)
    {
        $error = FALSE;

        foreach($comments as $products_id => $comment)
        {
            if ($this->exists($products_id))
            {
                if ($this->ci->wishlist_model->update_wishlist_product($this->wishlists_id, $products_id, $comment))
                {
                    $this->contents[$products_id]['comments'] = $comment;
                    $this->save_session();
                }
                else
                {
                    $error = TRUE;
                    break;
                }
            }
        }

        if ($error === FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Save data to session
     *
     * @access private
     * @return void
     */
    private function save_session()
    {
        $this->ci->session->set_userdata('wishlist_contents', array('contents' => $this->contents,
                	                                                'wishlists_id' => $this->wishlists_id, 
                	                                                'token' => $this->token));
    }

    /**
     * Get wishlist products
     *
     * @access public
     * @return array
     */
    public function get_products()
    {
        $products = array();

        if ($this->has_contents())
        {
            foreach ($this->contents as $products_id => $data)
            {
                $products[] = $data;
            }

            return $products;
        }
         
        return NULL;
    }

    /**
     * Synchronize with database
     * 
     * @access public
     * @return void
     */
    public function synchronize_with_database()
    {
        if (!$this->ci->customer->is_logged_on())
        {
            return;
        }

        $this->customers_id = $this->ci->customer->get_id();

        //get wishlist
        $data = $this->ci->wishlist_model->get_wishlist_by_customers_id($this->customers_id);
        if ($data !== NULL)
        {
            $products = $this->ci->wishlist_model->get_wishlist_products($this->wishlists_id);
            if (is_array($products) && !empty($products))
            {
                //delete temp wishlist
                $temp_wishlists_id = $this->wishlists_id;
                $contents = $this->contents;

                //reset wishlist
                $this->reset();

                //assign wishlists data
                $this->wishlists_id = $data['wishlists_id'];
                $this->token = $data['wishlists_token'];

                //assign products
                foreach ($products as $products_id)
                {
                    $product = load_product_library($products_id);

                    if ($product->is_valid())
                    {
                        $this->contents[$products_id] = array('products_id' => $products_id,
                                                              'name' => $product->get_title(),
                                                              'image' => $product->get_image(),
                                                              'price' => $product->get_price(), 
                        									  'date_added' => get_date_short(get_date_now()),
                                                              'variants' => array(), //variants is not support 
                                                              'comments' => '');
                    }
                    else 
                    {
                        //remove invalid products id from database
                        $this->ci->wishlist_model->delete_product($this->wishlists_id, $products_id);
                    }
                }
                
                //merge current wishlists
                foreach ($contents as $products_id => $content)
                {
                    $this->add_product($products_id);
                }
                
                //remove temp wishlists
                $this->ci->wishlist_model->delete_product($temp_wishlists_id);
                //remove temp wishlists
                $this->ci->wishlist_model->delete_wishlist($temp_wishlists_id);

                //save session
                $this->save_session();
            }
        }
        else
        {
            if ($this->has_wishlist_id())
            {
                //regenerate token
                $token = $this->generate_token();

                //update model
                $this->ci->wishlist_model->update_wishlist($this->wishlists_id, $this->customers_id, $token);

                //save session
                $this->save_session();
            }
        }
    }
    
    /**
     * Get products by token
     * 
     * @access public
     * @param token
     * @return array
     */
    public function get_products_by_token($token)
    {
        $products = $this->ci->wishlist_model->get_products_by_token($token);
        
        return $products;
    }
}

// END Wishlist Class

/* End of file wishlist.php */
/* Location: ./system/tomatocart/libraries/wishlist.php */