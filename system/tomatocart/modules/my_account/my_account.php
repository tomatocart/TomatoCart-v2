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
 * Module My Account Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */

class Mod_My_account extends TOC_Module 
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'my_account';

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
    protected $params = NULL;

    /**
     * My Account Module Constructor
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
        
        $this->title = lang('box_my_account_heading');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of article categories module
     */
    public function index()
    {
        return $this->load_view('index.php');
    }
}

/* End of file my_account.php */
/* Location: ./system/tomatocart/modules/my_account/my_account.php */