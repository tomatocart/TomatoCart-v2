<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
 * Google Sitemap library
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-library
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class TOC_Google_Sitemap {
	/**
	 * Reference to CodeIgniter instance
	 *
	 * @access private
	 * @var object
	 */
	private $ci;
	
	protected $_file_name = "sitemaps";
	protected $_save_path = "";
	protected $_base_url;
	protected $_max_file_size = 10485760; // 10 * 1024 * 1024
	protected $_max_entries = 50000;
	protected $_sefu = null ;

	protected $_products_change_freq = 'weekly';
	protected $_products_priority = 0.5;
	protected $_categories_change_freq = 'weekly';

	protected $_categories_priority = 0.5;
	protected $_articles_change_freq = 'weekly';
	protected $_articles_priority = 0.25;

	protected $_compression = false;
	protected $_file_array = array();

	/**
	 * Class contructor
	 * 
	 * @param string $products_change_freq
	 * @param real $products_priority
	 * @param string $categories_change_freq
	 * @param real $categories_priority
	 * @param string $articles_change_freq
	 * @param real $articles_priority
	 * @return void
	 */
	public function __construct( $config = array() ) {
		// Set the super object to a local variable for use later
		$this->ci = &get_instance();

		$this->_save_path = ROOTPATH;
		$this->_base_url = rtrim(rtrim(base_url(),'/'),'admin');

		$this->ci->load
				->library( 'category_tree',
						array( 'load_from_database' => TRUE,
								'load_all_categories' => TRUE,
								'load_from_cache' => FALSE));
		if(count($config)>0) {
			$this->initialize($config);
		}
		// 		$osC_CategoryTree = new osC_CategoryTree();

	}
	
	/**
	 * Initialize the user preferences
	 *
	 * Accepts an associative array as input, containing display preferences
	 *
	 * @param	array	config preferences
	 * @return	void
	 */
	public function initialize($config = array())
	{		
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
	}

	public function generateSitemap() {
		
		return $this->_createCategorySitemap()
				&& $this->_createProductSitemap()
				&& $this->_createArticleSitemap()
				&& $this->_createIndexSitemap();
	}

	public function setCompression($compression) {
		if ($compression == 1)
			$this->_compression = true;
	}
	
	
	public function getSubmitURL() {
		$sitemap_url = $this->_base_url . 'sitemapsIndex.xml';
		return htmlspecialchars( utf8_encode('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $sitemap_url));
	}
	
	protected function _createSitemapFile($file) {
		$file_name = $this->_save_path  . $this->_file_name . $file . '.xml';
			
		if ($this->_compression == true) {
			$file_name .= '.gz';
			$handle = gzopen($file_name,'wb9');
		} else {
			$handle = fopen($file_name, 'w');
		}
	
		$this->_file_array[] =  $file_name;
	
		return $handle;
	}
	protected function getTimestamp($date = '') {
		global $osC_Language;
	
		if (empty($date)) {
			$this->ci->load->helper('date');
			$date = now();
		}
	
		$year = substr($date, 0, 4);
		$month = (int)substr($date, 5, 2);
		$day = (int)substr($date, 8, 2);
		$hour = (int)substr($date, 11, 2);
		$minute = (int)substr($date, 14, 2);
		$second = (int)substr($date, 17, 2);
	
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
	function _createProductSitemap() {
// 		global $osC_Database;
			
		$num_of_entries = 0;
		$num_of_product_file = 0;
		try {
		$Qproducts = $this->ci->db
		->select('products_id, if( products_last_modified is null , products_date_added, products_last_modified ) as last_modified, products_ordered', FALSE)
		->from('products')
		->order_by('products_ordered','desc')
		->get();

		$handle = $this->_createSitemapFile('Products');
		$this->_writeFile($handle, $this->_createXmlHeader());
		
		foreach ($Qproducts->result() as $row)
		{
			$location = $this->_base_url .$this->_hrefLink('product', $row->products_id);
			$last_mod = date ("Y-m-d", $this->getTimestamp($row->last_modified));
	
			$this->_writeSitemapFile($handle, $this->_createUrlElement($location, $last_mod, $this->_products_change_freq, $this->_products_priority), $num_of_entries, $num_of_product_file, 'Product');
		}
		$this->_closeSitemapFile($handle);
			return true;
			
		} catch (Exception $e) {
			log_message('error', __FUNCTION__ .':'.$e->getMessage());
			return false;
		}
		
	}
	
	function _createCategorySitemap() {
	
		$num_of_entries = 0;
		$num_of_category_file = 0;
		try {
		$Qcategories = $this->ci->db
		->select('categories_id, if( last_modified is null , date_added, last_modified ) as last_modified', FALSE)
		->from('categories')
		->order_by('parent_id','asc')
		->order_by('sort_order','asc')
		->order_by('categories_id','asc')
		->get();
	
		$handle = $this->_createSitemapFile('Categories');
		$this->_writeFile($handle, $this->_createXmlHeader());
		foreach ($Qcategories->result() as $row)
		{  
			$location    = $this->_base_url . $this->_hrefLink('cpath', $this->ci->category_tree->get_full_cpath($row->categories_id));
			$last_mod    = date ("Y-m-d", $this->getTimestamp( $row->last_modified));
			
			$this->_writeSitemapFile($handle, $this->_createUrlElement($location, $last_mod, $this->_categories_change_freq, $this->_categories_priority), $num_of_entries, $num_of_category_file, 'Category');			
		}
		$this->_closeSitemapFile($handle);	
		return true;
		} catch (Exception $e) {
			log_message('error', __FUNCTION__ .':'.$e->getMessage());
			return false;
		}
	}
	
	function _createArticleSitemap() {
	
		$num_of_entries = 0;
		$num_of_article_file = 0;
		try {
		$Qarticles = $this->ci->db
		->select('articles_id , if( articles_last_modified  is null || articles_last_modified in (\'0000-00-00 00:00:00\'), articles_date_added, articles_last_modified  ) as last_modified', FALSE)
		->from('articles')
		->order_by('articles_order','asc')
		->order_by('articles_id','asc')
		->get();
		
		$handle = $this->_createSitemapFile('Articles');
		$this->_writeFile($handle, $this->_createXmlHeader());
		foreach ($Qarticles->result() as $row)
		{
			$location    = $this->_base_url . $this->_hrefLink('info', $row->articles_id);
			$last_mod    = date ("Y-m-d", $this->getTimestamp( $row->last_modified));
			$change_freq = $article_change_frequency;
	
			$this->_writeSitemapFile($handle, $this->_createUrlElement($location, $last_mod, $this->_articles_change_freq, $this->_articles_priority), $num_of_entries, $num_of_article_file, 'Article');
		}
		$this->_closeSitemapFile($handle);
	
		return true;
		} catch (Exception $e) {
			log_message('error', __FUNCTION__ .':'.$e->getMessage());
			return false;
		}
	}
	
	function _hrefLink($page, $parameters) {
		//$link = osc_href_link($page, $parameters, 'NONSSL', false);
	
		return index_page().'/'.$page.'/'.$parameters;//$this->_sefu->generateURL($link, $page, $parameters);
	}
	
	protected function _writeFile($handle, $data) {
		if ($this->_compression == true) {
			gzwrite($handle, $data);
		} else {
			fwrite($handle, $data);
		}
	}
	
	protected function _writeSitemapFile(&$handle, $data, &$num_of_entries, &$num_of_files, $type) {
		$num_of_entries++;
		$this->_writeFile($handle, $data);
	
		if ( ($num_of_entries >= $this->_max_entries) || (filesize(end($this->_file_array)) >= $this->_max_file_size)) {
			$num_of_entries = 0;
			$num_of_files++;
			$handle = $this->_recreateSitemap($handle, $type, $num_of_files);
		}
	}
	
	protected function _closeSitemapFile($handle) {
		if($this->_compression) {
			fwrite($handle, '</urlset>');
			fclose($handle);
		}else{
			gzwrite($handle, '</urlset>');
			gzclose($handle);
		}
	}
	
	protected function _createUrlElement($url, $last_mod, $change_freq, $priority) {
		$xml = "\t" . '<url>' . "\n";
		$xml .= "\t\t" . '<loc>' . $url . '</loc>' . "\n";
		$xml .= "\t\t" . '<lastmod>' . $last_mod . '</lastmod>' . "\n";
		$xml .= "\t\t" . '<changefreq>' . $change_freq . '</changefreq>' . "\n";
		$xml .= "\t\t" . '<priority>' . $priority . '</priority>' . "\n";
		$xml .= "\t" . '</url>' . "\n";
	
		return $xml;
	}
	
	protected function _createXmlHeader() {
		$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$xml .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";
	
		return $xml;
	}
	
	protected function _createIndexSitemap() {
		$handle = fopen($this->_save_path . $this->_file_name . 'Index.xml', 'w');
		$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$xml .= '<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";
		fwrite($handle, $xml);
	
		for($i = 0; $i < sizeof($this->_file_array); $i++) {
			$content = "\t". '<sitemap>' . "\n";
			$content .= "\t\t" . '<loc>'.$this->_base_url . basename($this->_file_array[$i]) . '</loc>' . "\n";
			$content .= "\t\t" . '<lastmod>'.date ("Y-m-d", filemtime($this->_save_path . basename($this->_file_array[$i]))).'</lastmod>' . "\n";
			$content .= "\t" . '</sitemap>' . "\n";
			fwrite($handle, $content);
		}
	
		fwrite($handle, '</sitemapindex>');
	
		fclose($handle);
	
		return true;
	}
	
	protected function _recreateSitemap($handle, $filename, $num_of_file) {
		$this->_closeSitemapFile($handle);
		$file = $filename . $num_of_file;
		$handle = $this->_createSitemapFile($file);
		$this->_writeFile($handle, $this->_createXmlHeader());
	
		return $handle;
	}
}
