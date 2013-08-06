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
 * Module Shop By Price Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */

class Mod_Shop_By_Price extends TOC_Module {
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'shop_by_price';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    protected $version = '1.0';

    /**
     * Module params
     *
     * @access private
     * @var string
     */
    protected $params = array();

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

        $this->title = lang('box_shop_by_price_heading');

        //load library
        $this->ci->load->library('currencies');

        //set the params
        $currencies = $this->ci->currencies->get_data();
        if (!empty($currencies))
        {
            foreach($currencies as $key => $currency)
            {
                $this->params[] = array('name' => 'MODULE_SHOP_BY_PRICE_' . $key,
                                        'title' => $currency['title'], 
                                        'type' => 'numberfield', 
                                        'value' => '', 
                                        'description' => 'price interval (Price seperated by ";")');
            }
        }

    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of the module
     */
    public function index()
    {
        //create url
        $url = '';
        
        //keywords
        $keywords = $this->ci->input->get('keywords');
        if ($keywords != NULL) 
        {
            $url .= '&keywords=' . $keywords;
        }
        
        //cpath
        $cpath = $this->ci->input->get('cPath');
        if ($cpath != NULL) 
        {
            $url .= '&cPath=' . $cpath;
        }
            
        //manufacturers
        $manufacturers = $this->ci->input->get('manufacturers');
            if ($manufacturers != NULL) 
        {
            $url .= '&manufacturers=' . $manufacturers;
        }

        //currencies
        if (isset($this->config['MODULE_SHOP_BY_PRICE_' . $this->ci->currencies->get_code()]))
        {
            //get the prices configured in the admin panel
            $prices = explode(';', $this->config['MODULE_SHOP_BY_PRICE_' . $this->ci->currencies->get_code()]);

            //setup the view data
            $data['prices'] = array();
            if (is_array($prices) && sizeof($prices) > 0)
            {
                for($n = 0; $n <= sizeof($prices); $n++)
                {
                    if ($n === 0)
                    {
                        $price_section = $this->ci->currencies->display_raw_price(0) . ' ~ ' . $this->ci->currencies->display_raw_price($prices[$n]);

                        $pfrom = 0;
                        $pto = $prices[$n];
                    }
                    elseif ($n == sizeof($prices))
                    {
                        $price_section = $this->ci->currencies->display_raw_price($prices[$n-1]) . ' + ';

                        $pfrom = $prices[$n-1];
                        $pto = NULL;
                    }
                    else
                    {
                        $price_section = $this->ci->currencies->display_raw_price($prices[$n-1]) . ' ~ ' . $this->ci->currencies->display_raw_price($prices[$n]);

                        $pfrom = $prices[$n-1];
                        $pto = $prices[$n];
                    }

                    $data['prices'][] = array('link_text' => $price_section, 'link_href' => site_url('search?pfrom=' . $pfrom . '&pto=' . $pto . $url));
                }
            }

            //load view
            return $this->load_view('index.php', $data);
        }

        return NULL;
    }
}

/* End of file shop_by_price.php */
/* Location: ./system/tomatocart/modules/shop_by_price/shop_by_price.php */