<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
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
 * Index Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-index-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Index extends TOC_Controller
{

    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default Function
     *
     * @access public
     * @param string
     */
    public function index($view = null)
    {
        //load header
        $this->load->view('modules/header');

        //load view
        switch ($view) {
            case 'check':
                $this->load->view('check', array('step' => 2));
                break;
            case 'database':
                $this->load->view('database', array('step' => 3));
                break;
            case 'setting':
                $this->load->view('setting', array('step' => 4));
                break;
            case 'finish':
                $this->load->view('finish', array('step' => 5));
                break;
            case 'licence':
            default:
                //Get GNU lisence
                $licence = file_get_contents('applications/language/' . lang_code() . '/license.txt');

                //reder the view
                $this->load->view('licence', array('step' => 1, 'licence' => $licence));
        }

        //load footer
        $this->load->view('modules/footer');
    }
}

/* End of file index.php */
/* Location: ./install/applications/controllers/index.php */