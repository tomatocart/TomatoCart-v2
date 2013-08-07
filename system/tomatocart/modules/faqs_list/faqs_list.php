<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Module Faqs Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Mod_Faqs_List extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'faqs_list';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    var $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    var $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    var $version = '1.0';
    
    /**
     * Template Module Parameters
     *
     * @access private
     * @var string
     */
    var $params = array(
        array('name' => 'BOX_FAQ_MAX_LIST',
              'title' => 'Maximum List Size', 
              'type' => 'numberfield',
              'value' => '10',
              'description' => 'Maximum amount of faq to show in the listing'));
    
    /**
     * Faqs Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();
        
        $this->title = lang('box_faqs_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of faqs module
     */
    public function index()
    {
        //load model
        $this->ci->load->model('info_model');
        
        //setup view data
        $faqs = $this->ci->info_model->get_faqs(lang_id());
        
        if ($faqs != NULL)
        {
            foreach($faqs as $faq) 
            {
                $links[] = array('link' => site_url('info/faqs/' . $faq['faqs_id']),
                                 'title' => $faq['faqs_question']);
            }
        }

        return $this->load_view('index.php', array('links' => $links));
    }
}