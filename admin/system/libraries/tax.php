<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TOC_Tax
 *
 * @author TomatoCart Dev Team
 */
class TOC_Tax {
  protected $ci = null;
  protected $tax_rates = array();

  /**
   * Toc Tax Constructor
   */
  function __construct() {
    $this->ci = get_instance();

    $this->ci->load->model('tax_model');
  }

  /**
   * Get tax rate
   *
   * @param $class_id
   * @param $country_id
   * @param $zone_id
   */
  function get_tax_rate($class_id, $country_id = NULL, $zone_id = NULL)
  {
    if (empty($country_id) && empty($zone_id)) {
      $country_id = STORE_COUNTRY;
      $zone_id = STORE_ZONE;
    }
    
    if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['rate']) == FALSE)
    {
      $tax_rates = $this->ci->tax_model->get_tax_rate($country_id, $zone_id, $class_id);
      
      if (!empty($tax_rates))
      {
        $tax_multiplier = 1.0;
        foreach($tax_rates as $tax_rate)
        {
          $tax_multiplier *= 1.0 + ($tax_rate['tax_rate'] / 100);
        }
        
        $tax_rate = ($tax_multiplier - 1.0) * 100;
      }
      else
      {
        $tax_rate = 0;
      }
      
      $this->tax_rates[$class_id][$country_id][$zone_id]['rate'] = $tax_rate;
    }
    
    return $this->tax_rates[$class_id][$country_id][$zone_id]['rate'];
  }

  /**
   * Get tax rate description
   *
   * @param $class_id
   * @param $country_id
   * @param $zone_id
   */
  function get_tax_rate_description($class_id, $country_id, $zone_id)
  {
    if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['description']) == FALSE)
    {
      $descriptons = $this->ci->tax_model->get_tax_rate_description($country_id, $zone_id, $class_id);
      if ($descriptons !== FALSE)
      {
        $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = implode(' + ', $descriptons);
      }
      else
      {
        $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = lang('tax_rate_unknown');
      }
    }

    return $this->tax_rates[$class_id][$country_id][$zone_id]['description'];
  }

  /**
   * Calculate tax.
   *
   * @param $price
   * @param $tax_rate
   */
  function calculate($price, $tax_rate)
  {
    return round($price * $tax_rate / 100, $this->ci->currencies->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
  }

  /**
   * display tax rate value.
   *
   * @param $value
   * @param $padding
   */
  function display_tax_rate_value($value, $padding = null)
  {
    if (!is_numeric($padding))
    {
      $padding = config('TAX_DECIMAL_PLACES');
    }

    if (strpos($value, '.') !== FALSE)
    {
      while (true)
      {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }

          break;
        }
      }
    }

    if ($padding > 0)
    {
      if (($decimal_pos = strpos($value, '.')) !== FALSE)
      {
        $decimals = strlen(substr($value, ($decimal_pos+1)));

        for ($i=$decimals; $i<$padding; $i++)
        {
          $value .= '0';
        }
      }
      else
      {
        $value .= '.';

        for ($i=0; $i<$padding; $i++)
        {
          $value .= '0';
        }
      }
    }

    return $value . '%';
  }
}
// END Tax Class

/* End of file tax.php */
/* Location: ./system/tomatocart/libraries/tax.php */