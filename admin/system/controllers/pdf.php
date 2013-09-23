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
 * Pdf Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Pdf extends TOC_Controller
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
	
	// ------------------------------------------------------------------------
	
	/**
	 * Print order pdf
	 *
	 * @access public
	 * @return string
	 */
	public function print_order($orders_id)
	{
		//load order pdf library under pdf sub-directory
		$this->load->library('pdf/order_pdf', $orders_id);
		
		$pdf_output = $this->order_pdf->render();
		
		$this->output->set_content_type('application/pdf')->set_output($pdf_output);
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Print invoice pdf
	 *
	 * @access public
	 * @return string
	 */
	public function print_invoice($orders_id)
	{
		//load inoice pdf library under pdf sub-directory
		$this->load->library('pdf/invoice_pdf', $orders_id);
		
		$pdf_output = $this->invoice_pdf->render();
		
		$this->output->set_content_type('application/pdf')->set_output($pdf_output);
	}
}

/* End of file pdf.php */
/* Location: ./system/controllers/pdf.php */