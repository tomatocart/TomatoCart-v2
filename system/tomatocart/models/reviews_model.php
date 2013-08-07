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
 * Reviews Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-reviews-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Reviews_Model extends CI_Model
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
     * Save review
     *
     * @access public
     * @param $data
     * @return boolean
     */
    function save_review($data)
    {
        if ( is_array($data['rating']) ) {
            $total = 0;
            foreach($data['rating'] as $value) {
                $total += $value;
            }

            $reviews_rating =  $total/ count($data['rating']);
        } else {
            $reviews_rating = $data['rating'];
        }

        //review data
        $reviews = array(
            'products_id' => $data['products_id'],
            'customers_id' => $data['customer_id'],
            'customers_name' => $data['customer_name'],
            'reviews_rating' => $reviews_rating,
            'languages_id' => lang_id(),
            'reviews_text' => $data['review'],
            'reviews_status' => 1);

        $this->db->insert('reviews', $reviews);

        //rating data
        if ( is_array($data['rating']) )
        {
            //get insert id
            $reviews_id = $this->db->insert_id();

            foreach($data['rating'] as $ratings_id => $value)
            {
                //order data
                $rating = array(
                'ratings_id' => $ratings_id,
                'customers_id' => $data['customer_id'],
                'reviews_id' => $reviews_id,
                'ratings_value' => $value);

                $this->db->insert('customers_ratings', $rating);
            }
        }
    }
}

/* End of file reviews_model.php */
/* Location: ./system/tomatocart/models/reviews_model.php */