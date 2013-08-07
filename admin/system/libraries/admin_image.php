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

class TOC_Admin_Image extends TOC_Image {
  private $_title;
  private $_header;
  private $_data = array();
  private $_has_parameters = false;
  
  public function __construct()
  {
    parent::__construct();
    
    $this->ci->load->model('admin_image_model');
  }
  
  public function &getGroups()
  {
    return $this->_groups;
  }
  
  public function resize($image, $group_id, $type = 'products') 
  {
    return $this->resizeWithGD($image, $group_id, $type);
  }
  
  public function hasGDSupport() {
    if ( imagetypes() & ( IMG_JPG || IMG_GIF || IMG_PNG ) ) 
    {
      return true;
    }

    return false;
  }
  
  public function resizeWithGD($image, $group_id, $type) {
    if (!file_exists(ROOTPATH . 'images/' . $type . '/' . $this->_groups[$group_id]['code'])) {
      mkdir(ROOTPATH . 'images/' . $type . '/' . $this->_groups[$group_id]['code'], 0777);
    }
    
    $original_image = ROOTPATH . 'images/' . $type . '/' . $this->_groups[1]['code'] . '/' . $image;
    $dest_image = ROOTPATH . 'images/' . $type . '/' . $this->_groups[$group_id]['code'] . '/' . $image;
    
    if (file_exists($original_image)) {
      $config['image_library'] = 'gd2';
      $config['source_image'] = $original_image;
      $config['new_image'] = $dest_image;
      $config['maintain_ratio'] = TRUE;
      $config['width']   = $this->_groups[$group_id]['size_width'];
      $config['height'] = $this->_groups[$group_id]['size_height'];
      
      $this->ci->load->library('image_lib');
      
      $this->ci->image_lib->initialize($config);
      
      return $this->ci->image_lib->resize();
    }
  }
  
  public function getModuleCode() 
  {
    return $this->_code;
  }
  
  public function &getTitle() 
  {
    return $this->_title;
  }
  
  public function &getHeader() 
  {
    return $this->_header;
  }
  
  public function &getData() 
  {
    return $this->_data;
  }
  
  public function activate() 
  {
    $this->_setHeader();
    $this->_setData();
  }
  
  public function hasParameters() 
  {
    return $this->_has_parameters;
  }
  
  public function setAsDefault($id)
  {
    $affected_rows = $this->ci->admin_image_model->set_as_default($id);
    
    if ($affected_rows === 1)
    {
      return true;
    }
    
    return false;
  }
  
  public function delete($id)
  {
    $image = $this->ci->admin_image_model->get_image_name($id);
    
    if (!empty($image) && is_array($image))
    {
      $image_name = $image['image'];
      
      foreach ($this->_groups as $group) {
        @unlink(ROOTPATH. 'images/products/' . $group['code'] . '/' . $image_name);
      }
      
     //remove watermark file
      if (file_exists(ROOTPATH. 'images/products/' . $this->_groups[1]['code'] . '/watermark_' . $image_name)) {
        @unlink(DROOTPATH . 'images/products/' . $this->_groups[1]['code'] . '/watermark_' . $image_name);
      }
      
      $deleted = $this->ci->admin_image_model->delete($id);
      
      return ($deleted === 1);
    }
  }
  
  public function delete_articles_image($id)
  {
    $image = $this->ci->admin_image_model->get_articles_image($id);
    
    foreach($this->_groups as $group)
    {
      @unlink(ROOTPATH . 'images/articles/' . $group['code'] . '/' . $image['articles_image']);
    }
  }
}

/* End of file admin_image.php */
/* Location: ./system/library/admin_image.php */