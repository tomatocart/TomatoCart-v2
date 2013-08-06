<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package   CodeIgniter
 * @author    ExpressionEngine Dev Team
 * @copyright Copyright (c) 2006 - 2011, EllisLab, Inc.
 * @license   http://codeigniter.com/user_guide/license.html
 * @link    http://codeigniter.com
 * @since   Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Image Class
 *
 * @package   CodeIgniter
 * @subpackage  Libraries
 * @category  Shopping Cart
 * @author    ExpressionEngine Dev Team
 * @link    http://codeigniter.com/user_guide/libraries/cart.html
 */

Class TOC_Image {
  protected $_groups;
  protected $ci;
  
  public function __construct()
  {
    // Set the super object to a local variable for use later
    $this->ci =& get_instance();
    
    $this->ci->load->model('image_model');
    $this->_groups = array();
    
    $image_groups = $this->ci->image_model->get_groups();
    
    if (!empty($image_groups))
    {
      foreach($image_groups as $image_group)
      {
        $this->_groups[$image_group['id']] = $image_group;
      }
    }
  }
  
  public function getID($code) 
  {
    foreach ($this->_groups as $group) 
    {
      if ($group['code'] == $code) 
      {
        return $group['id'];
      }
    }

    return 0;
  }
  
  public function getCode($id) 
  {
    return $this->_groups[$id]['code'];
  }
  
  public  function getWidth($code) 
  {
    return $this->_groups[$this->getID($code)]['size_width'];
  }
  
  public function getHeight($code) 
  {
    return $this->_groups[$this->getID($code)]['size_height'];
  }
  
  public function exists($code) 
  {
    return isset($this->_groups[$this->getID($code)]);
  }
  
  public function show($image, $title, $parameters = '', $group = '', $type = 'products') 
  {
    if (empty($group) || !$this->exists($group)) 
    {
      $group = $this->getCode(DEFAULT_IMAGE_GROUP_ID);
    }

    $group_id = $this->getID($group);

    $width = $height = '';

    if ( ($this->_groups[$group_id]['force_size'] == '1') || empty($image) ) 
    {
      $width = $this->_groups[$group_id]['size_width'];
      $height = $this->_groups[$group_id]['size_height'];
    }

    if (empty($image))
    {
      $image = 'no_image.png';
    } else 
    {
      $image = $type . '/' . $this->_groups[$group_id]['code'] . '/' . $image;
    }

    if ($type == 'products')
    {
      $parameters .= 'class="productImage"';
    }
  }
}


/* End of file image.php */
/* Location: ./system/libraries/image.php */
