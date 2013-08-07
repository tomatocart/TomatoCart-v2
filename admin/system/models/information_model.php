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
 * Information Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Information_Model extends CI_Model
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
    
    // --------------------------------------------------------------------

    /**
     * Get the articles
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_articles($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('a.articles_id, a.articles_status, a.articles_order, ad.articles_name, acd.articles_categories_name')
            ->from('articles a')
            ->join('articles_description ad', 'a.articles_id = ad.articles_id')
            ->join('articles_categories_description acd', 'acd.articles_categories_id = a.articles_categories_id and acd.language_id = ad.language_id')
            ->where(array('acd.articles_categories_id' => 1, 'ad.language_id' => lang_id()));
        
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the total numbers of the articles
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->where('articles_categories_id', 1)->from('articles')->count_all_results();
    }
}

/* End of file information_model.php */
/* Location: ./system/models/information_model.php */