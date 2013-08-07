<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Registry
 *
 * Gives registry functionality.  Allows you to save code 'globally'.
 *
 * @author TomatoCart Dev Team
 */
class TOC_Registry {

  /* Where everything is stored */
  private $data = array();

  public function __construct() {}

  /**
   * Set Magic Method
   *
   * Sets data to the registry
   *
   * @param string $name
   * @param mixed $value
   */
  public function  __set($name, $value)
  {
    $this->set($name, $value);
  }



  /**
   * Get Magic Method
   *
   * Gets from the registry
   *
   * @param string $name
   * @return mixed
   */
  public function __get($name)
  {
    return $this->get($name);
  }



  /**
   * Set
   *
   * Sets to the registry
   *
   * @param string $name
   * @param mixed $value
   */
  final public function set($name, $value)
  {
    $this->data[$name] = $value;
  }



  /**
   * Get
   *
   * Gets from the registry
   *
   * @param string $name
   * @return mixed
   */
  final public function get($name)
  {
    if(array_key_exists($name, $this->data))
    {
      return $this->data[$name];
    }
    else
    {
      return FALSE;
    }
  }
}
// END Registry Class

/* End of file registry.php */
/* Location: ./system/tomatocart/libraries/registry.php */