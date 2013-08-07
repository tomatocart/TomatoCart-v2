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
 * Module Follow Us Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */

class Mod_Follow_us extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'follow_us';

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
     * Template Module Params
     *
     * @access private
     * @var array
     */
    protected $params = array(
        //MODULE_FOLLOW_US_FACEBOOK_LINK
        array('name' => 'MODULE_FOLLOW_US_FACEBOOK_LINK',
              'title' => 'Facebook Link', 
              'type' => 'textfield',
              'value' => 'https://www.facebook.com/tomatocart',
              'description' => 'Facebook Link'),
        
        //MODULE_FOLLOW_US_TWITTER_LINK
        array('name' => 'MODULE_FOLLOW_US_TWITTER_LINK',
              'title' => 'Twitter Link', 
              'type' => 'textfield',
              'value' => 'https://twitter.com/tomatocart',
              'description' => 'Twitter Link'),
        
        //MODULE_FOLLOW_US_GOOGLE_PLUS_LINK
        array('name' => 'MODULE_FOLLOW_US_GOOGLE_PLUS_LINK',
              'title' => 'Google Plus Link', 
              'type' => 'textfield',
              'value' => 'https://plus.google.com/109588253708268031594',
              'description' => 'Google Plus Link'));

    /**
     * Follow Module Constructor
     *
     * @access public
     */
    public function __construct($config)
    {
        parent::__construct();

        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, TRUE);
        }
        
        $this->title = lang('box_follow_us_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of article categories module
     */
    public function index()
    {
        return $this->load_view('index.php', array(
        	'facebook_link' => $this->config['MODULE_FOLLOW_US_FACEBOOK_LINK'], 
        	'twitter_link' => $this->config['MODULE_FOLLOW_US_TWITTER_LINK'], 
        	'google_plus_link' => $this->config['MODULE_FOLLOW_US_GOOGLE_PLUS_LINK']));
    }
}

/* End of file follow_us.php */
/* Location: ./system/tomatocart/modules/follow_us/follow_us.php */