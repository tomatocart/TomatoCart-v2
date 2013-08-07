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
 * Invoices Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
Class Invoices extends TOC_Controller
{
    /**
     * Store the customer informations
     *
     * @var array
     */
    private $_customer_info;
    
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
     * List the invoices
     *
     * @access public
     * @return string
     */
    public function list_invoices()
    {
        $this->load->library('currencies');
        $this->load->library('address');
        $this->load->helper('date');
        
        $this->load->model('invoices_model');
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $orders_id = $this->input->get_post('orders_id');
        $customers_id = $this->input->get_post('customers_id');
        $status = $this->input->get_post('status');
        
        $invocies = $this->invoices_model->get_invoices($start, $limit, $orders_id, $customers_id, $status);
        
        $records = array();
        if ($invocies != NULL)
        {
            foreach($invocies as $invoice)
            {
                $this->load->library('order', $invoice['orders_id']);
                
                $order_details = $this->get_order_total($this->order);
                
                $records[] = array('orders_id' => $invoice['orders_id'], 
                                   'customers_name' => $invoice['customers_name'], 
                                   'order_total' => $invoice['order_total'], 
                                   'date_purchased' => mdate('%Y-%m-%d', human_to_unix($invoice['date_purchased'])), 
                                   'orders_status_name' => $invoice['orders_status_name'], 
                                   'invoices_number' => $invoice['invoice_number'], 
                                   'invoices_date' => mdate('%Y-%m-%d', human_to_unix($invoice['invoice_date'])), 
                                   'shipping_address' => $this->address->format($this->order->get_delivery(), '<br />'),
                                   'shipping_method' => $this->order->get_deliver_method(),
                                   'billing_address' => $this->address->format($this->order->get_billing(), '<br />'),
                                   'payment_method' => $this->order->get_payment_method(), 
                                   'products' => $order_details['products_table'], 
                                   'totals' => $order_details['order_total']);
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->invoices_model->get_total($orders_id, $customers_id, $status),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Create the invoic
     *
     * @access public
     * @return string
     */
    public function create_invoice($orders_id)
    {
        $this->load->helper('date');
        $this->load->library('pdf');
        $this->load->library('order', $orders_id);
        $this->load->library('currencies');
        
        $customer_info = $this->order->get_billing();
        $customer_info['email_address'] = $this->order->get_customer('email_address');
        
        $this->set_customer_info($customer_info);
        
        $this->pdf->SetCreator('TomatoCart');
        $this->pdf->SetAuthor('TomatoCart');
        $this->pdf->SetTitle(lang('pdf_invoice_heading_title'));
        $this->pdf->SetSubject($orders_id . ': ' . $customer_info['name']);
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        //Add Page
        $this->pdf->AddPage(); 
          
        //Title
        $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TITLE_FONT_SIZE);
        $this->pdf->SetY(TOC_PDF_POS_HEADING_TITLE_Y);
        $this->pdf->MultiCell(70, 4, lang('pdf_invoice_heading_title'), 0, 'L');
        
        //Set Header
        
        $this->set_header();
          
        //Date purchase & order ID field title
        $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE);
        $this->pdf->SetY(TOC_PDF_POS_DOC_INFO_FIELD_Y);
        $this->pdf->SetX(135);
        $this->pdf->MultiCell(55, 4, lang('operation_heading_invoice_number') . ':' . "\n" . lang('operation_heading_invoice_date') . ':' . "\n" . lang('operation_heading_order_id') . ':' , 0, 'L');
    
        //Date purchase & order ID field value
        $this->pdf->SetFont(TOC_PDF_FONT, '', TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE);
        $this->pdf->SetY(TOC_PDF_POS_DOC_INFO_FIELD_Y);
        $this->pdf->SetX(150);
        $this->pdf->MultiCell(40, 4, $this->order->get_invoice_number() . "\n" . mdate('%Y-%m-%d', human_to_unix($this->order->get_invoice_date())) . "\n" . $this->order->get_order_id(), 0, 'R');
          
        //Products
        $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_HEADING_FONT_SIZE);
        $this->pdf->SetY(TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y);
        $this->pdf->Cell(8, 6, '', 'TB', 0, 'R', 0);
        $this->pdf->Cell(78, 6, lang('table_heading_products'), 'TB', 0, 'C', 0);
        $this->pdf->Cell(35, 6,  lang('table_heading_quantity'), 'TB', 0, 'C', 0);
        $this->pdf->Cell(30, 6, lang('table_heading_price'), 'TB', 0, 'R', 0);
        $this->pdf->Cell(30, 6, lang('table_heading_total'), 'TB', 0, 'R', 0);
        $this->pdf->Ln();
          
        //end here
        $i = 0;
        $y_table_position = TOC_PDF_POS_PRODUCTS_TABLE_CONTENT_Y;
        foreach ($this->order->get_products() as $products) 
        {
            $rowspan = 1;
            
            //Pos
            $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_CONTENT_FONT_SIZE);
            $this->pdf->SetY($y_table_position);
            $this->pdf->MultiCell(8, 4, ($i + 1), 0, 'C');
          
            //Product
            $this->pdf->SetY($y_table_position);
            $this->pdf->SetX(30);
            
            $product_info = $products['name'];
            if (strlen($products['name']) > 30) 
            {
                $rowspan = 2;
            }
            
            if ( $products['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE ) 
            {
                $product_info .= "\n" . '   -' . lang('senders_name') . ': ' . $products['senders_name'];
                
                if ($products['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) 
                {
                    $product_info .= "\n" . '   -' . lang('senders_email') . ': ' . $products['senders_email'];
                    $rowspan++;
                }
                
                $product_info .= "\n" . '   -' . lang('recipients_name') . ': ' . $products['recipients_name'];
                
                if ($products['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) 
                {
                    $product_info .= "\n" . '   -' . lang('recipients_email') . ': ' . $products['recipients_email'];
                    $rowspan++;
                }
                
                $rowspan += 3;
                $product_info .= "\n" . '   -' . lang('messages') . ': ' . $products['messages'];
            }
            
            if (isset( $products['variants'] ) && ( sizeof( $products['variants'] ) > 0)) 
            {
                foreach ( $products['variants'] as $variant ) 
                {
                    $product_info .=  "\n" . $variant['groups_name'] . ": " . $variant['values_name'];
                    $rowspan++;
                } 
            } 
            $this->pdf->MultiCell(80, 4, $product_info, 0, 'L');          
      
            //Quantity
            $this->pdf->SetY($y_table_position);
            $this->pdf->SetX( 110 );
            $this->pdf->MultiCell(5, 4, $products['quantity'], 0, 'C');
            
            //Price
            $this->pdf->SetY($y_table_position);
            $this->pdf->SetX(135);
            $price = $this->currencies->display_price_with_tax_rate($products['final_price'], $products['tax'], 1, $this->order->get_currency(), $this->order->get_currency_value());
            $price = str_replace('&nbsp;',' ',$price);
            $this->pdf->MultiCell(20, 4, $price, 0, 'R');
            
            //Total
            $this->pdf->SetY($y_table_position);
            $this->pdf->SetX(165);
            $total = $this->currencies->display_price_with_tax_rate($products['final_price'], $products['tax'], $products['quantity'], $this->order->get_currency(), $this->order->get_currency_value());
            $total = str_replace('&nbsp;', ' ', $total);
            $this->pdf->MultiCell(20, 4, $total, 0, 'R');
            
            $y_table_position += $rowspan * TOC_PDF_TABLE_CONTENT_HEIGHT;
            
            //products list exceed page height, create a new page
            if (($y_table_position - TOC_PDF_POS_CONTENT_Y - 6) > 160) 
            { 
                $this->pdf->AddPage();
                
                $y_table_position = TOC_PDF_POS_CONTENT_Y + 6;
                $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_HEADING_FONT_SIZE);
                $this->pdf->SetY(TOC_PDF_POS_CONTENT_Y);
                $this->pdf->Cell(8, 6, '', 'TB', 0, 'R', 0);
                $this->pdf->Cell(78, 6, lang('table_heading_products'), 'TB', 0, 'C', 0);
                $this->pdf->Cell(35, 6,  lang('table_heading_quantity'), 'TB', 0, 'C', 0);
                $this->pdf->Cell(30, 6, lang('table_heading_price'), 'TB', 0, 'R', 0);
                $this->pdf->Cell(30, 6, lang('table_heading_total'), 'TB', 0, 'R', 0);
                $this->pdf->Ln();
            }      
            $i++;
        }
        
        $this->pdf->SetY($y_table_position + 1);
        $this->pdf->Cell(180, 7, '', 'T', 0, 'C', 0);
    
        //Order Totals
        $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_CONTENT_FONT_SIZE);
        foreach ( $this->order->get_totals() as $totals ) 
        {
            $y_table_position += 4;
            
            $this->pdf->SetFont(TOC_PDF_FONT, 'B', 8);
            $this->pdf->SetY($y_table_position);
            $this->pdf->SetX(40);
            $this->pdf->MultiCell(120, 5, $totals['title'], 0, 'R');
            
            $total_text = str_replace('&nbsp;', ' ', $totals['text']);
            
            $this->pdf->SetFont(TOC_PDF_FONT, 'B', 8);
            $this->pdf->SetY($y_table_position);
            $this->pdf->SetX(145);
            $this->pdf->MultiCell(40, 5, strip_tags($total_text), 0, 'R');
        }
    
        $this->pdf->Output("Invoice.pdf", "I");
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Set the customer information
     *
     * @access private
     * @param $customer_info
     * @return void
     */
    private function set_customer_info($customer_info)
    {
        $this->_customer_info = $customer_info;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the site logo
     *
     * @access private
     * @return mixed
     */
    private function get_original_logo()
    {
        $this->load->library('directory_listing', array('directory' => ROOTPATH . 'images', 'stats' => TRUE));
        $this->directory_listing->set_include_directories(FALSE);
        
        foreach($this->directory_listing->get_files() as $file)
        {
            $filename = explode(".", $file['name']);
            
            if ($filename[0] == 'logo_originals')
            {
                return ROOTPATH . 'images/' . 'logo_originals.' . $filename[1];
            }
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Set the invoice header
     *
     * @access private
     * @return void
     */
    private function set_header()
    {
        //logo
        $logo = $this->get_original_logo();
        $logo = ($logo === FALSE) ? (ROOTPATH . 'images/store_logo.jpg') : $logo;
        $this->pdf->Image($logo, TOC_PDF_LOGO_UPPER_LEFT_CORNER_X, TOC_PDF_LOGO_UPPER_LEFT_CORNER_Y, TOC_PDF_LOGO_WIDTH, TOC_PDF_LOGO_HEIGHT);
        
        //Line
        $this->pdf->line(10, 45, 98, 45);
        
        //Billing Information
        $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE);
        $this->pdf->SetY(TOC_PDF_POS_ADDRESS_INFO_Y);
        $this->pdf->MultiCell(100, 4, $this->_customer_info['name'] . "\n" . 
                                 $this->_customer_info['street_address'] . " " . $this->_customer_info['suburb'] . "\n" .
                                 $this->_customer_info['postcode'] . " " . $this->_customer_info['city'] . "\n" .
                                 $this->_customer_info['country_title']  . "\n" . 
                                 $this->_customer_info['email_address'], 0, 'L');
    
        //Store Address
        $this->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_STORE_ADDRESS_FONT_SIZE);
        $this->pdf->SetY(TOC_PDF_POS_STORE_ADDRESS_Y);
        $this->pdf->Cell(100);
        $this->pdf->MultiCell(80, 4, STORE_NAME_ADDRESS, 0 ,'R');
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the order totals
     *
     * @access private
     * @param $order
     * @return array
     */
    private function get_order_total($order)
    {
        $products_table = '<table width="100%">';
        foreach($order->get_products() as $product)
        {
            $product_info = $product['quantity'] . '&nbsp;x&nbsp;' . $product['name'];
            
            if ( $product['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE ) 
            {
                $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('senders_name') . ': ' . $product['senders_name'] . '</i></nobr>';
                
                if ($product['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) 
                {
                    $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('senders_email') . ': ' . $product['senders_email'] . '</i></nobr>';
                }
                
                $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('recipients_name') . ': ' . $product['recipients_name'] . '</i></nobr>';
                
                if ($product['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) 
                {
                    $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('recipients_email') . ': ' . $product['recipients_email'] . '</i></nobr>';
                }
                
                $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . lang('messages') . ': ' . $product['messages'] . '</i></nobr>';
            }
            
            if ( isset($product['variants']) && is_array($product['variants']) && ( sizeof($product['variants']) > 0 ) ) 
            {
                foreach ( $product['variants'] as $variants ) 
                {
                    $product_info .= '<br /><nobr>&nbsp;&nbsp;&nbsp;<i>' . $variants['groups_name'] . ': ' . $variants['values_name'] . '</i></nobr>';
                }
            }
            
            if ( isset($product['customizations']) && !empty($product['customizations']) ) 
            {
                $product_info .= '<p>';
                
                foreach ($product['customizations'] as $key => $customization) 
                {
                    $product_info .= '<div style="float: left">' . $customization['qty'] . ' x ' . '</div>';
                    $product_info .= '<div style="margin-left: 25px">';
                    
                    foreach ($customization['fields'] as $orders_products_customizations_values_id => $field) 
                    {
                        if ($field['customization_type'] == CUSTOMIZATION_FIELD_TYPE_INPUT_TEXT) {
                            $product_info .= $field['customization_fields_name'] . ': ' . $field['customization_value'] . '<br />';
                        } else {
                            $product_info .= $field['customization_fields_name'] . ': <a href="' . '#">' . '</a>' . '<br />';
                        }
                    }
                    
                    $product_info .= '</div>';
                }
                
                $product_info .= '</p>';
            }
            
            $products_table .= '<tr><td>' . $product_info . '</td><td width="60" valign="top" align="right">' . $this->currencies->display_price_with_tax_rate($product['final_price'], $product['tax'], 1, $this->order->get_currency(), $this->order->get_currency_value()) . '</td></tr>';
        }
        $products_table .= '</table>';
        
        $order_total = '<table width="100%">';
        foreach($order->get_totals() as $total)
        {
            $order_total .= '<tr><td align="right">' . $total['title'] . '&nbsp;&nbsp;&nbsp;</td><td width="60" align="right">' . $total['text'] . '</td></tr>';
        }
        $order_total .= '</table>';
        
        return array('products_table' => $products_table, 'order_total' => $order_total);
    }
}

/* End of file invoices.php */
/* Location: ./system/controllers/invoices.php */