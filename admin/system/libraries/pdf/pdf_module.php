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
 * Pdf Module Class
 *
 * This class is the parent class for all toc pdf classes
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Pdf_Module 
{
	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;
	
	/**
	 * Hold the customer information
	 *
	 * @var array
	 */
	protected $customer_info = array();
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @param int
	 * @return void
	 */
	public function __construct($orders_id = NULL)
	{
		// Set the super object to a local variable for use later
		$this->CI =& get_instance();
		
		//load products helper
		$this->CI->load->helper('products');
		
		//load library
		$this->CI->load->library('pdf');
		
		//check the orders id to initialize the order instance correctly
		if ($orders_id == NULL || ! is_numeric($orders_id))
		{
			log_message('error', 'An invalid orders id was passed into constructer of TOC_Pdf_Module class');
		}
		else
		{
			$this->CI->load->library('order', $orders_id);
		}
		
		//set customer info
		$this->set_customer_info();
		
		log_message('debug', 'Pdf Module Class Initialized');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Render the pdf
	 *
	 * @access public
	 * @return string
	 */
	protected function render()
	{
		//load the date helper
		$this->CI->load->helper('date');
		
		//set general pdf info
		$this->set_general_info();
		
		//new Page
		$this->CI->pdf->AddPage();
		
		//set header
		$this->set_header();
		
		//print products table
		$y_table_position = $this->print_products_table();
		
		//print order totals
		$this->print_order_totals($y_table_position);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the customer information
	 *
	 * @access protected
	 * @param array
	 * @return void
	 */
	protected function set_customer_info($customer_info = array())
	{
		if (count($customer_info) > 0)
		{
			$this->customer_info = $customer_info;
		}
		else
		{
			$this->customer_info = $this->CI->order->get_billing();
			$this->customer_info['email_address'] = $this->CI->order->get_customer('email_address');
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the general information of this pdf
	 *
	 * @access protected
	 * @return void
	 */
	protected function set_general_info()
	{
		$this->CI->pdf->SetSubject($this->CI->order->get_order_id() . ': ' . $this->customer_info['name']);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the header of the pdf
	 *
	 * @access protected
	 * @return void
	 */
	protected function set_header()
	{
		//get the logo data from the config file under local/config/tcpdf.php
		$header_data = $this->CI->pdf->getHeaderData();
		
		//ouput the logo
		$this->CI->pdf->Image(ROOTPATH . 'images/' . $header_data['logo'], TOC_PDF_LOGO_UPPER_LEFT_CORNER_X, TOC_PDF_LOGO_UPPER_LEFT_CORNER_Y, $header_data['logo_width']);
		
		//Line
		$this->CI->pdf->line(10, 45, 98, 45);
		
		//Billing Information
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_ADDRESS_INFO_Y);
		$this->CI->pdf->MultiCell(100, 4,
						$this->customer_info['name'] . "\n" .
						$this->customer_info['street_address'] . " " . $this->customer_info['suburb'] . "\n" .
						$this->customer_info['postcode'] . " " . $this->customer_info['city'] . "\n" .
						$this->customer_info['country_title']  . "\n" .
						$this->customer_info['email_address'], 0, 'L'
		);
		
		//Store Address
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_STORE_ADDRESS_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_STORE_ADDRESS_Y);
		$this->CI->pdf->Cell(100);
		$this->CI->pdf->MultiCell(80, 4, STORE_NAME_ADDRESS, 0 ,'R');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the title of head fields such as date purchase and order id
	 *
	 * @access protected
	 * @param array
	 * @return void
	 */
	protected function set_head_fields($fields)
	{
		//Date purchase & order ID field title
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_DOC_INFO_FIELD_Y);
		$this->CI->pdf->SetX(135);
		$this->CI->pdf->MultiCell(55, 4, implode(':' . "\n", $fields['titles']) . ':' , 0, 'L');
		
		$this->CI->pdf->SetFont(TOC_PDF_FONT, '', TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_DOC_INFO_FIELD_Y);
		$this->CI->pdf->SetX(150);
		$this->CI->pdf->MultiCell(40, 4, implode("\n", $fields['values']) , 0, 'R');
	}
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Print products table
	 *
	 * @access protected
	 * @return int
	 */
	protected function print_products_table()
	{
		//products
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_HEADING_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y);
		$this->CI->pdf->Cell(8, 6, '', 'TB', 0, 'R', 0);
		$this->CI->pdf->Cell(78, 6, lang('table_heading_products'), 'TB', 0, 'C', 0);
		$this->CI->pdf->Cell(15, 6, lang('table_heading_quantity'), 'TB', 0, 'C', 0);
		$this->CI->pdf->Cell(40, 6, lang('table_heading_price'), 'TB', 0, 'C', 0);
		$this->CI->pdf->Cell(40, 6, lang('table_heading_total'), 'TB', 0, 'C', 0);
		$this->CI->pdf->Ln();
		
		//print products
		$i = 0;
		$y_table_position = TOC_PDF_POS_PRODUCTS_TABLE_CONTENT_Y;
		if (count($this->CI->order->get_products()) > 0)
		{
			foreach ($this->CI->order->get_products() as $product)
			{
				$rowspan = 1;
		
				//pos
				$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_CONTENT_FONT_SIZE);
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->MultiCell(8, 4, ($i + 1), 0, 'C');
		
				//product
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->SetX(30);
		
				$product_info = $product['name'];
				if (strlen($product['name']) > 30)
				{
					$rowspan = 2;
				}
		
				//currently, ingore the gift certificate
		
				//variants
				if (isset($product['variants']) && count($product['variants']) > 0)
				{
					foreach ($product['variants'] as $variant)
					{
						$product_info .=  "\n" . $variant['groups_name'] . ": " . $variant['values_name'];
						$rowspan++;
					}
				}
		
				$this->CI->pdf->MultiCell(80, 4, $product_info, 0, 'L');
		
				//quantity
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->SetX( 105 );
				$this->CI->pdf->MultiCell(5, 4, $product['quantity'], 0, 'C');
		
				//price
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->SetX(122);
				$price = $this->CI->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], 1, $this->CI->order->get_currency(), $this->CI->order->get_currency_value());
				$price = str_replace('&nbsp;',' ',$price);
				$this->CI->pdf->MultiCell(20, 4, $price, 0, 'R');
		
				//total
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->SetX(163);
				$total = $this->CI->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], $product['quantity'], $this->CI->order->get_currency(), $this->CI->order->get_currency_value());
				$total = str_replace('&nbsp;', ' ', $total);
				$this->CI->pdf->MultiCell(20, 4, $total, 0, 'R');
		
				$y_table_position += $rowspan * TOC_PDF_TABLE_CONTENT_HEIGHT;
		
				//products list exceed page height, create a new page
				if (($y_table_position - TOC_PDF_POS_CONTENT_Y - 6) > 160)
				{
					$this->CI->pdf->AddPage();
		
					$y_table_position = TOC_PDF_POS_CONTENT_Y + 6;
					$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_HEADING_FONT_SIZE);
					$this->CI->pdf->SetY(TOC_PDF_POS_CONTENT_Y);
					$this->CI->pdf->Cell(8, 6, '', 'TB', 0, 'R', 0);
					$this->CI->pdf->Cell(78, 6, lang('table_heading_products'), 'TB', 0, 'C', 0);
					$this->CI->pdf->Cell(35, 6,  lang('table_heading_quantity'), 'TB', 0, 'C', 0);
					$this->CI->pdf->Cell(30, 6, lang('table_heading_price'), 'TB', 0, 'R', 0);
					$this->CI->pdf->Cell(30, 6, lang('table_heading_total'), 'TB', 0, 'R', 0);
					$this->CI->pdf->Ln();
				}
		
				$i++;
			}
		}
		
		return $y_table_position;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Print order totals
	 *
	 * @access protected
	 * @param int
	 * @return void
	 */
	protected function print_order_totals($y_table_position)
	{
		$this->CI->pdf->SetY($y_table_position + 1);
		$this->CI->pdf->Cell(180, 7, '', 'T', 0, 'C', 0);
		
		//order totals
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_CONTENT_FONT_SIZE);
		if (count($this->CI->order->get_totals()) > 0)
		{
			foreach ($this->CI->order->get_totals() as $total)
			{
				$y_table_position+= 4;
		
				$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', 8);
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->SetX(40);
				$this->CI->pdf->MultiCell(120, 5, $total['title'], 0, 'R');
		
				$total_text = str_replace('&nbsp;', ' ', $total['text']);
		
				$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', 8);
				$this->CI->pdf->SetY($y_table_position);
				$this->CI->pdf->SetX(145);
				$this->CI->pdf->MultiCell(40, 5, strip_tags($total_text), 0, 'R');
			}
		}
	}
}

/* End of file pdf_module.php */
/* Location: ./system/libraries/pdf/pdf_module.php */