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
 * Manufacturers Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Mod_Manufacturers extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'manufacturers';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    protected $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    protected $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    protected $version = '1.0';

    /**
     * Template Module Parameter
     *
     * @access private
     * @var string
     */
    protected $params = array(
        array('name' => 'BOX_MANUFACTURERS_LIST_TYPE',
              'title' => 'Manufacturers List Type', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Image List',
              'description' => 'The type of the manufacturers list(ComboBox, Image List).',
              'values' => array(
                  array('id' => 'ComboBox', 'text' => 'ComboBox'),
                  array('id' => 'Image List', 'text' => 'Image List'))),
         array('name' => 'BOX_MANUFACTURERS_LIST_SIZE', 
              'title' => 'Manufacturers List Size', 
              'type' => 'numberfield',
    		  'value' => '1',
              'description' => 'The size of the manufacturers pull down menu listing.'));
  
    /**
     * Module Constructor
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();
        
        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, TRUE);
        }
  
        $this->title = lang('box_manufacturers_heading');
    }
  
    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of the module
     */
    public function index()
    {
        //load model
        $this->ci->load->model('manufacturers_model');
        
        //setup view data
        $manufacturers = $this->ci->manufacturers_model->get_manufacturers();
        
        if ($manufacturers != NULL) 
        {
            $data = array(
            	'list_type' => $this->config['BOX_MANUFACTURERS_LIST_TYPE'], 
            	'list_size' => $this->config['BOX_MANUFACTURERS_LIST_SIZE'],
                'manufacturers' => $manufacturers
            );
            
            //load view
            return $this->load_view('index.php', $data);
        }
        
        return NULL;
    }
}

/* End of file manufacturers.php */
/* Location: ./system/tomatocart/modules/manufacturers/manufacturers.php */