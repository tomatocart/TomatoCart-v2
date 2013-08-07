<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TOC_Currencies
 *
 * @author TomatoCart Dev Team
 */
class TOC_Currencies {
  /***/
  protected $ci = null;
  /***/
  protected $currencies = array();

  /**
   * Toc Currencies Constructor
   */
  public function __construct()
  {
    // Set the super object to a local variable for use later
    $this->ci = get_instance();

    //Load currencies data from database
    $this->ci->load->model('currencies_model');
    $this->currencies = $this->ci->currencies_model->get_all();
  }

  /**
   * Format currency value with currency symbol
   *
   * @param $number
   * @param $currency_code
   * @param $currency_value
   */
  public function format($number, $currency_code = '', $currency_value = '')
  {
    //if currency code is empty
    if (empty($currency_code) || ($this->exists($currency_code) == FALSE))
    {
      $currency_code = ($this->ci->session->userdata('currency') === TRUE) ? $this->ci->session->userdata('currency') : config('DEFAULT_CURRENCY');
    }
    
    //if currency value is empty
    if (empty($currency_value) || (is_numeric($currency_value) == FALSE))
    {
      $currency_value = $this->currencies[$currency_code]['value'];
    }
    
    return $this->currencies[$currency_code]['symbol_left'] . number_format(round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator()) . $this->currencies[$currency_code]['symbol_right'];
  }

  /**
   * Format currency raw value
   *
   * @param $number
   * @param $currency_code
   * @param $currency_value
   */
  function format_raw($number, $currency_code = '', $currency_value = '')
  {
    //if currency code is empty
    if (empty($currency_code) || ($this->exists($currency_code) == FALSE))
    {
      $currency_code = ($this->ci->session->userdata('currency') === FALSE) ? $this->ci->session->userdata('currency') : config('DEFAULT_CURRENCY');
    }

    //if currency value is empty
    if (empty($currency_value) || (is_numeric($currency_value) == FALSE))
    {
      $currency_value = $this->currencies[$currency_code]['value'];
    }

    return number_format(round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], '.', '');
  }

  /**
   * Add tax value to price
   *
   * @param $price
   * @param $tax_rate
   * @param $quantity
   */
  function add_tax_rate_to_price($price, $tax_rate, $quantity = 1)
  {
    $price = round($price, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);

    if ( (config('DISPLAY_PRICE_WITH_TAX') == '1') && ($tax_rate > 0) )
    {
      $price += round($price * ($this->ci->tax->get_tax_rate($tax_class_id) / 100), $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
    }

    return round($price * $quantity, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

  /**
   * Show product price
   *
   * @param $price
   * @param $tax_rate
   * @param $quantity
   */
  function display_price($price, $tax_class_id, $quantity = 1, $currency_code = null, $currency_value = null)
  {
    $price = round($price, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);

    if ((config('DISPLAY_PRICE_WITH_TAX') == '1') && ($tax_class_id > 0))
    {
      $price += round($price * ($this->ci->tax->get_tax_rate($tax_class_id) / 100), $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
    }

    return $this->format($price * $quantity, $currency_code, $currency_value);
  }

  /**
   * Show product price with given tax
   *
   * @param $price
   * @param $tax_rate
   * @param $quantity
   * @param $currency_code
   * @param $currency_value
   */
  function display_price_with_tax_rate($price, $tax_rate, $quantity = 1, $currency_code = '', $currency_value = '')
  {
    $price = round($price, $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);

    if ( (config('DISPLAY_PRICE_WITH_TAX') == '1') && ($tax_rate > 0) )
    {
      $price += round($price * ($tax_rate / 100), $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places']);
    }

    return $this->format($price * $quantity, $currency_code, $currency_value);
  }

  /**
   * Display raw price without tax
   *
   * @param $number
   * @param $currency_code
   */
  function display_raw_price($number, $currency_code = '')
  {
    if (empty($currency_code) || ($this->exists($currency_code) == FALSE))
    {
      $code = $this->ci->session->userdata('currency');
      $currency_code = (isset($code) ? $code : config('DEFAULT_CURRENCY'));
    }

    return $this->currencies[$currency_code]['symbol_left'] . number_format(round($number, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $this->ci->lang->get_numeric_decimal_separator(), $this->ci->lang->get_numeric_thousands_separator()) . $this->currencies[$currency_code]['symbol_right'];
  }

  /**
   * Check whether the currencies code exist.
   *
   * @param $code
   */
  function exists($code)
  {
    if (isset($this->currencies[$code]))
    {
      return true;
    }

    return FALSE;
  }

  /**
   * Get decimal places of specified currency code.
   *
   * @param $code
   */
  function decimal_places($code)
  {
    if ($this->exists($code))
    {
      return $this->currencies[$code]['decimal_places'];
    }

    return FALSE;
  }

  /**
   * Get currency value of specified currency
   *
   * @param $code
   */
  function value($code)
  {
    if ($this->exists($code))
    {
      return $this->currencies[$code]['value'];
    }

    return FALSE;
  }

  /**
   * Get all currencies data
   */
  function get_data()
  {
    return $this->currencies;
  }

  /**
   * Get currencies code by id
   *
   * @param $id
   */
  function get_code($id = '')
  {
    if (is_numeric($id))
    {
      foreach ($this->currencies as $key => $value)
      {
        if ($value['id'] == $id)
        {
          return $key;
        }
      }
    }
    else
    {
      return $this->session->userdata('currency');
    }
  }

  /**
   * Get symbol left by id
   *
   * @param $id
   */
  function get_symbol_left($id = '')
  {
    if (is_numeric($id))
    {
      foreach ($this->currencies as $key => $value)
      {
        if ($value['id'] == $id) {
          return $value['symbol_left'];
        }
      }
    }
    else
    {
      return $this->currencies[config('DEFAULT_CURRENCY')]['symbol_left'];
    }
  }

  /**
   * Get symbol right by id
   *
   * @param $id
   */
  function get_symbol_right($id = '')
  {
    if (is_numeric($id))
    {
      foreach ($this->currencies as $key => $value)
      {
        if ($value['id'] == $id)
        {
          return $value['symbol_right'];
        }
      }
    }
    else
    {
      return $this->currencies[config('DEFAULT_CURRENCY')]['symbol_right'];
    }
  }

  /**
   * Get decimal places by id
   *
   * @param $id
   */
  function get_decimal_places($id = '')
  {
    if (is_numeric($id))
    {
      foreach ($this->currencies as $key => $value)
      {
        if ($value['id'] == $id)
        {
          return $value['decimal_places'];
        }
      }
    }
    else
    {
      return $this->currencies[config('DEFAULT_CURRENCY')]['decimal_places'];
    }
  }

  /**
   * Get id by code
   *
   * @param $code
   */
  function get_id($code = '')
  {
    if (empty($code))
    {
      $code = $this->session->userdata('currency');
    }

    return $this->currencies[$code]['id'];
  }
}
// END Currencies Class

/* End of file currencies.php */
/* Location: ./system/tomatocart/libraries/currencies.php */