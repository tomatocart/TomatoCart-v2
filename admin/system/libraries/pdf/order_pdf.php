<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
*/

class TOC_Order_Pdf 
{
	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct($orders_id)
	{
		// Set the super object to a local variable for use later
		$this->CI =& get_instance();
		
		//load helper
		$this->CI->load->helper('products');
		
		//load library
		$this->CI->load->library('pdf');
		$this->CI->load->library('order', $orders_id);
		
		$customer_info = $this->CI->order->get_billing();
		$customer_info['email_address'] = $this->CI->order->get_customer('email_address');
		
		$this->CI->pdf->SetCreator('TomatoCart');
		$this->CI->pdf->SetAuthor('TomatoCart');
		$this->CI->pdf->SetTitle('Order');
		$this->CI->pdf->SetSubject($this->CI->order->get_order_id() . ': ' . $customer_info['name']);
		
		//set customer info
		$this->CI->pdf->set_customer_info($customer_info);
	}
	
	/**
	 * Render the order pdf
	 *
	 * @access public
	 * @return string
	 */
	public function render()
	{
		$this->CI->load->helper('date');
		
		//new Page
		$this->CI->pdf->AddPage();
		
		//title
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TITLE_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_HEADING_TITLE_Y);
		$this->CI->pdf->MultiCell(70, 4, lang('pdf_order_heading_title'), 0, 'L');
		
		//date purchase & order ID field title
		$this->CI->pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_DOC_INFO_FIELD_Y);
		$this->CI->pdf->SetX(135);
		$this->CI->pdf->MultiCell(55, 4, lang('operation_heading_order_date') . ':' . "\n" . lang('operation_heading_order_id') . ':' , 0, 'L');
		
		//date purchase & order ID field value
		$this->CI->pdf->SetFont(TOC_PDF_FONT, '', TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE);
		$this->CI->pdf->SetY(TOC_PDF_POS_DOC_INFO_VALUE_Y);
		$this->CI->pdf->SetX(150);
		$this->CI->pdf->MultiCell(40, 4, mdate($this->CI->lang->get_date_format_short(), mysql_to_unix($this->CI->order->get_date_created())) . "\n" . $this->CI->order->get_order_id()  , 0, 'R');
		
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
		
		//return output pdf
		return $this->CI->pdf->Output('Order', 'S');
	}
}

/* End of order_pdf.php */
/* Location: ./system/libraries/order_pdf.php */