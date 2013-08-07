<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @license   http://www.tomatocart.com/doc-license
 * @link    http://www.tomatocart.com
 */

Class Admin_Image_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function set_as_default($id)
  {
    $Qimage = $this->db
    ->select('products_id')
    ->from('products_images')
    ->where('id', $id)
    ->get();
    
    $image = $Qimage->row_array();
    
    $Qimage->free_result();
    
    if (!empty($image))
    {
      $this->db->update('products_images', array('default_flag' => 0), array('products_id' => $image['products_id'], 'default_flag' => 1));
      
      $this->db->update('products_images', array('default_flag' => 1), array('id' => $id));
      
      return $this->db->affected_rows();
    }
    
    return FALSE;
  }
  
  public function get_image_name($id)
  {
    $Qimage = $this->db
    ->select('image')
    ->from('products_images')
    ->where('id', $id)
    ->get();
    
    if ($Qimage->num_rows() > 0)
    {
      return $Qimage->row_array();
    }
    
    return false;
  }
  
  public function delete($id)
  {
    $Qdel = $this->db->delete('products_images', array('id' => $id));

    return $this->db->affected_rows();
  }
  
  public function get_articles_image($id)
  {
    $Qimage = $this->db
    ->select('articles_image')
    ->from('articles')
    ->where('articles_id', $id)
    ->get();
    
    return $Qimage->row_array();
  }
}
