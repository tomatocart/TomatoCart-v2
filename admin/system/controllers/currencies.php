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
 * Currencies Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Currencies extends TOC_Controller
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
        
        $this->load->model('currencies_model');
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * List the currencies
     *
     * @access public
     * @return string
     */
    public function list_currencies()
    {
        $this->load->library('currencies');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $currencies = $this->currencies_model->get_currencies($start, $limit);
        
        $records = array();
        if ($currencies !== NULL)
        {
            foreach($currencies as $currency)
            {
                $currency_name = $currency['title'];
                
                if ($currency['code'] == DEFAULT_CURRENCY)
                {
                    $currency_name .= ' (' . lang('default_entry') . ')';
                }
                
                $records[] = array('currencies_id' => $currency['currencies_id'],
                                   'title' => $currency_name,
                                   'code' => $currency['code'],
                                   'value' => $currency['value'],
                                   'example' => $this->currencies->format(1499.99, $currency['code'], 1));
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->currencies_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Save the currency
     *
     * @access public
     * @return string
     */
    public function save_currency()
    {
        $error = FALSE;
        $feedback = array();
        
        $code = $this->input->post('code');
        $currencies_id = $this->input->post('currencies_id');
        
        //add new currency that is already existed
        if (!is_numeric($currencies_id) && $this->currencies_model->code_exist($code))
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_currency_code_exist');
        }
        
        if ($error === FALSE)
        {
            $data = array('title' => $this->input->post('title'),
                          'code' => $code,
                          'symbol_left' => $this->input->post('symbol_left'),
                          'symbol_right' => $this->input->post('symbol_right'),
                          'decimal_places' => $this->input->post('decimal_places'),
                          'value' => $this->input->post('value'));
            
            $default = FALSE;
            if ( ($this->input->post('default') == 'on') || ($this->input->post('is_default') == 'on' && $code != DEFAULT_CURRENCY) )
            {
                $default = TRUE;
            }
            
            if ($this->currencies_model->save($currencies_id, $default, $data))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Load the currency
     *
     * @access public
     * @return string
     */
    public function load_currency()
    {
        $data = $this->currencies_model->get_data($this->input->post('currencies_id'));
        
        if ($data['code'] == DEFAULT_CURRENCY) 
        {
            $data['is_default'] = '1';
        }
        
        $this->output->set_output(json_encode(array('success' => TRUE, 'data' => $data)));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Delete the currency
     *
     * @access public
     * @return string
     */
    public function delete_currency()
    {
        $error = FALSE;
        $feedback = array();
        
        $code = $this->input->post('code');
        if ($code == DEFAULT_CURRENCY) 
        {
            $error = TRUE;
            $feedback[] = lang('introduction_delete_currency_invalid');
        }
        
        if ($error === FALSE)
        {
            if ($this->currencies_model->delete($this->input->post('cID')))
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Batch delete the currencies
     *
     * @access public
     * @return string
     */
    public function delete_currencies()
    {
        $error = FALSE;
        $feedback = array();
        
        $currencies_ids = json_decode($this->input->post('batch'));
        $currencies = $this->currencies_model->get_currencies_info($currencies_ids);
        
        //check the default currency
        if ($currencies !== NULL)
        {
            foreach($currencies as $currency)
            {
                if ($currency['code'] == DEFAULT_CURRENCY)
                {
                    $error = TRUE;
                    $feedback[] = lang('introduction_delete_currency_invalid');
                    
                    break;
                }
            }
        }
        
        if ($error === FALSE)
        {
            //delete the currencies
            if (count($currencies_ids) > 0)
            {
                foreach($currencies_ids as $id)
                {
                    if ($this->currencies_model->delete($id) === FALSE)
                    {
                        $error = TRUE;
                        
                        break;
                    }
                }
            }
            else
            {
                $error = TRUE;
            }
           
            if ($error === FALSE)
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
  
    // ------------------------------------------------------------------------
  
    /**
     * Update the currency rates
     *
     * @access public
     * @return string
     */
    public function update_currency_rates()
    {
        if ($this->currencies_model->update_rates($this->input->post('currencies_id'), 'oanda'))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file currencies.php */
/* Location: ./system/controllers/currencies.php */