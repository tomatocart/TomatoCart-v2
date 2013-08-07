<?php

# override the default TCPDF config file
if(!defined('K_TCPDF_EXTERNAL_CONFIG')) {	
	define('K_TCPDF_EXTERNAL_CONFIG', TRUE);
}
	
# include TCPDF
require(APPPATH.'config/tcpdf'.EXT);
require_once($tcpdf['base_directory'].'/tcpdf.php');

define('TOC_PDF_POS_START_X', 70);
define('TOC_PDF_POS_START_Y', 50);
define('TOC_PDF_LOGO_UPPER_LEFT_CORNER_X', 100);
define('TOC_PDF_LOGO_UPPER_LEFT_CORNER_Y', 10);
define('TOC_PDF_LOGO_WIDTH', 80);
define('TOC_PDF_LOGO_HEIGHT', 20);

define('TOC_PDF_POS_ADDRESS_INFO_Y', TOC_PDF_POS_START_Y);
define('TOC_PDF_POS_STORE_ADDRESS_Y', TOC_PDF_POS_START_Y);
define('TOC_PDF_POS_CONTENT_Y', (TOC_PDF_POS_START_Y + 40));
define('TOC_PDF_POS_HEADING_TITLE_Y', TOC_PDF_POS_CONTENT_Y);
define('TOC_PDF_POS_DOC_INFO_FIELD_Y', TOC_PDF_POS_CONTENT_Y);
define('TOC_PDF_POS_DOC_INFO_VALUE_Y', TOC_PDF_POS_CONTENT_Y);
define('TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y', (TOC_PDF_POS_CONTENT_Y + 20));
define('TOC_PDF_POS_PRODUCTS_TABLE_CONTENT_Y', (TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y + 6));

define('TOC_PDF_FONT', 'times');
define('TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE', 11);
define('TOC_PDF_HEADER_STORE_ADDRESS_FONT_SIZE', 9);
define('TOC_PDF_FOOTER_PAGEING_FONT_SIZE', 8);
define('TOC_PDF_TITLE_FONT_SIZE', 14);
define('TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE', 9);
define('TOC_PDF_TABLE_HEADING_FONT_SIZE', 10);
define('TOC_PDF_TABLE_CONTENT_FONT_SIZE', 9);
define('TOC_PDF_TABLE_CONTENT_HEIGHT', 5);
define('TOC_PDF_TABLE_PRODUCT_VARIANT_FONT_SIZE', 8);
define('TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE', 10);
define('TOC_PDF_SHIP_TO_TITLE_FONT_SIZE', 11);



/************************************************************
 * TCPDF - CodeIgniter Integration
 * Library file
 * ----------------------------------------------------------
 * @author Jonathon Hill http://jonathonhill.net
 * @version 1.0
 * @package tcpdf_ci
 ***********************************************************/
class TOC_Pdf extends TCPDF {
	
	
	/**
	 * TCPDF system constants that map to settings in our config file
	 *
	 * @var array
	 * @access private
	 */
	private $cfg_constant_map = array(
		'K_PATH_MAIN'	=> 'base_directory',
		'K_PATH_URL'	=> 'base_url',
		'K_PATH_FONTS'	=> 'fonts_directory',
		'K_PATH_CACHE'	=> 'cache_directory',
		'K_PATH_IMAGES'	=> 'image_directory',
		'K_BLANK_IMAGE' => 'blank_image',
		'K_SMALL_RATIO'	=> 'small_font_ratio',
	);
	
	
	/**
	 * Settings from our APPPATH/config/tcpdf.php file
	 *
	 * @var array
	 * @access private
	 */
	private $_config = array();
	
	
	/**
	 * Initialize and configure TCPDF with the settings in our config file
	 *
	 */
	function __construct() {
		
		# load the config file
		require(APPPATH.'config/tcpdf'.EXT);
		$this->_config = $tcpdf;
		unset($tcpdf);
		
		
		
		# set the TCPDF system constants
		foreach($this->cfg_constant_map as $const => $cfgkey) {
			if(!defined($const)) {
				define($const, $this->_config[$cfgkey]);
				#echo sprintf("Defining: %s = %s\n<br />", $const, $this->_config[$cfgkey]);
			}
		}
		
		# initialize TCPDF		
		parent::__construct(
			$this->_config['page_orientation'], 
			$this->_config['page_unit'], 
			$this->_config['page_format'], 
			$this->_config['unicode'], 
			$this->_config['encoding'], 
			$this->_config['enable_disk_cache']
		);
		
		
		# language settings
		if(is_file($this->_config['language_file'])) {
			include($this->_config['language_file']);
			$this->setLanguageArray($l);
			unset($l);
		}
		
		# margin settings
		$this->SetMargins($this->_config['margin_left'], $this->_config['margin_top'], $this->_config['margin_right']);
		
		# header settings
		$this->print_header = $this->_config['header_on'];
		#$this->print_header = FALSE; 
		$this->setHeaderFont(array($this->_config['header_font'], '', $this->_config['header_font_size']));
		$this->setHeaderMargin($this->_config['header_margin']);
		$this->SetHeaderData(
			$this->_config['header_logo'], 
			$this->_config['header_logo_width'], 
			$this->_config['header_title'], 
			$this->_config['header_string']
		);
		
		# footer settings
		$this->print_footer = $this->_config['footer_on'];
		$this->setFooterFont(array($this->_config['footer_font'], '', $this->_config['footer_font_size']));
		$this->setFooterMargin($this->_config['footer_margin']);
		
		# page break
		$this->SetAutoPageBreak($this->_config['page_break_auto'], $this->_config['footer_margin']);
		
		# cell settings
		$this->cMargin = $this->_config['cell_padding'];
		$this->setCellHeightRatio($this->_config['cell_height_ratio']);
		
		# document properties
		$this->author = $this->_config['author'];
		$this->creator = $this->_config['creator'];
		
		# font settings
		#$this->SetFont($this->_config['page_font'], '', $this->_config['page_font_size']);
		
		# image settings
		$this->imgscale = $this->_config['image_scale'];
		
	}
	
	
	
}