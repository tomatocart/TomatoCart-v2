<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Shopping Cart Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Shopping Cart
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/cart.html
 */
class TOC_Product {

  protected $data = array();
  protected $customers_id = null;
  protected $customers_groups_id = null;
  protected $customer_group_discount = null;

  /**
   * Shopping Class Constructor
   *
   * The constructor loads the Session class, used to store the shopping cart contents.
   */
  public function __construct($id = '')
  {

    //initialize the ci instance
    $this->ci = get_instance();

    //cache
    $this->ci->load->driver('cache', array('adapter' => 'file'));

    if (!empty($id)) {
      $this->data = $this->ci->cache->get('product-' . $id . '-' . $this->ci->lang->get_code());

      if ($this->data === FALSE) {
        $this->ci->load->model('products_model');
        $this->data = $this->ci->products_model->get_data($id);

        $this->ci->cache->save('product-' . $id . '-' . $this->ci->lang->get_code(), $this->data, 30000);
      }

      //format the variants display price
      if ( isset($this->data['variants']) && is_array($this->data['variants']) )
      {
        $products_variants = $this->data['variants'];

        foreach ($products_variants as $products_id_string => $data)
        {
          $this->data['variants'][$products_id_string]['display_price'] = $this->ci->currencies->display_rice($data['price'], $this->data['tax_class_id']);
        }
      }

      //get quantity discount group
      if ($this->data['quantity_discount_groups_id'] > 0)
      {
        $this->data['quantity_discount'] = $this->get_discount_group();
      }
    }
  }
  
  

  public function get_average_reviews_rating()
  {
    if ($this->_reviews_average_rating == NULL)
    {
      $this->_reviews_average_rating = round($this->products_model->get_average_reviews_rating($this->data['id']));
    }

    return $this->_reviews_average_rating;
  }
  
  public function restock($orders_id, $orders_products_id, $products_id, $products_quantity)
  {
    $this->ci->load->model('products_model');
    
    $restock = $this->ci->products_model->restock($orders_id, $orders_products_id, $products_id, $products_quantity);
    
    return $restock;
  }
  
  function get_discount_group()
  {
    $customers_groups_id = $this->ci->customer->get_customers_groups_id();
    $quantity_discount_groups_id = $this->ci->customer->get_quantity_discount_groups_id();
    $discount_group = $this->products_model->get_discount_group($quantity_discount_groups_id, $customers_groups_id);

    //if quantity discount groups exists and discount_group is not found then try to get default one
    if ( ($quantity_discount_groups_id > 0) && ($discount_group === FALSE) )
    {
      $discount_group = $this->products_model->get_discount_group($quantity_discount_groups_id);
    }

    return $discount_group;
  }

  public function get_product_variants_id($variants)
  {
    $product_id_string = get_product_id_string($this->get_id(), $variants);

    if(isset($this->data['variants']) && isset($this->data['variants'][$product_id_string]))
    {
      return $this->data['variants'][$product_id_string]['variants_id'];
    }
    else
    {
      return FALSE;
    }
  }

  function get_variants_combobox_array()
  {
    if ($this->has_variants()) {
      $combobox_array = array();

      foreach ($this->data['variants_groups'] as $groups_id => $groups_name)
      {
        $values = array();
        foreach($this->data['variants_groups_values'][$groups_id] as $values_id)
        {
          $values[] = array('id' => $values_id, 'text' => $this->data['variants_values'][$values_id]);
        }

        //        $combobox_array[$groups_name] = osc_draw_pull_down_menu(
        //            'variants[' . $groups_id . ']',
        //        $values,
        //        $this->data['default_variant']['groups_id'][$groups_id]);
      }
      return $combobox_array;
    }

    return false;
  }

  function get_default_variant()
  {
    if ($this->has_variants())
    {
      return $this->data['default_variant'];
    }

    return false;
  }

  function has_quantity_discount()
  {
    return (isset($this->data['quantity_discount']) && !empty($this->data['quantity_discount']));
  }

  function get_quantity_discount($quantity)
  {
    $quantity_discount = 0;
    if($this->has_quantity_discount())
    {
      $quantities = array_keys($this->data['quantity_discount']);
      for ($i = sizeof($quantities); $i > 0; $i--)
      {
        if($quantity >= $quantities[$i-1])
        {
          $quantity_discount = $this->data['quantity_discount'][$quantities[$i-1]];
          break;
        }
      }
    }
    return $quantity_discount;
  }

  function is_valid()
  {
    if (empty($this->data))
    {
      return FALSE;
    }

    return TRUE;
  }

  function get_data($key = NULL)
  {
    if ($key === NULL)
    {
      return $this->data;
    }
    else
    {
      if (isset($this->data[$key]))
      {
        return $this->data[$key];
      }
    }

    return FALSE;
  }

  function get_id()
  {
    return $this->data['id'];
  }

  function get_title()
  {
    return $this->data['name'];
  }

  function get_product_type()
  {
    return $this->data['type'];
  }

  function is_simple()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_SIMPLE));
  }

  function is_virtual()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_VIRTUAL));
  }

  function is_downloadable()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_DOWNLOADABLE));
  }

  function has_sample_file()
  {
    return (isset($this->data['sample_filename']) && !empty($this->data['sample_filename']));
  }

  function get_sample_file()
  {
    return $this->data['sample_filename'];
  }

  function is_gift_certificate()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE));
  }

  function get_gift_certificate_type()
  {
    if ($this->isGiftCertificate())
    {
      return $this->data['gift_certificates_type'];
    }

    return FALSE;
  }

  function is_email_gift_certificate()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE) && ($this->data['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL));
  }

  function is_physical_gift_certificate()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE) && ($this->data['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_PHYSICAL));
  }

  function is_fix_amount_gift_certificate()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE) && ($this->data['gift_certificates_amount_type'] == GIFT_CERTIFICATE_TYPE_FIX_AMOUNT));
  }

  function is_open_amount_gift_certificate()
  {
    return (isset($this->data['type']) && ($this->data['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE) && ($this->data['gift_certificates_amount_type'] == GIFT_CERTIFICATE_TYPE_OPEN_AMOUNT));
  }

  function get_open_amount_min_value()
  {
    return $this->data['open_amount_min_value'];
  }

  function get_open_amount_max_value()
  {
    return $this->data['open_amount_max_value'];
  }

  function get_short_description()
  {
    return $this->data['products_short_description'];
  }

  function get_description()
  {
    return $this->data['description'];
  }

  function has_model() {
    return (isset($this->data['model']) && !empty($this->data['model']));
  }

  function get_model($variants = null) {
    if ($variants == null || empty($variants)) {
      return $this->data['model'];
    } else {
      $product_id_string = get_product_id_string($this->get_id(), $variants);

      return $this->data['variants'][$product_id_string]['model'];
    }
  }

  function has_sku() {
    return (isset($this->data['sku']) && !empty($this->data['sku']));
  }

  function get_sku($variants = null) {
    if ($variants == null || empty($variants))
    {
      $sku = $this->data['sku'];

      if (is_array($this->data['default_variant']) && !empty($this->data['default_variant']))
      {
        $sku = $this->data['default_variant']['sku'];
      }

      return $sku;
    }
    else
    {
      $product_id_string = get_product_id_string($this->get_id(), $variants);

      return $this->data['variants'][$product_id_string]['sku'];
    }
  }

  function has_keyword()
  {
    return (isset($this->data['keyword']) && !empty($this->data['keyword']));
  }

  function get_keyword()
  {
    return $this->data['keyword'];
  }

  function has_page_title()
  {
    return (isset($this->data['page_title']) && !empty($this->data['page_title']));
  }

  function get_page_title()
  {
    return $this->data['page_title'];
  }

  function has_meta_keywords()
  {
    return (isset($this->data['meta_keywords']) && !empty($this->data['meta_keywords']));
  }

  function get_meta_keywords()
  {
    return $this->data['meta_keywords'];
  }

  function has_meta_description()
  {
    return (isset($this->data['meta_description']) && !empty($this->data['meta_description']));
  }

  function get_meta_description()
  {
    return $this->data['meta_description'];
  }

  function has_tags()
  {
    return (isset($this->data['tags']) && !empty($this->data['tags']));
  }

  function get_tags()
  {
    return $this->data['tags'];
  }

  function get_moq()
  {
    return $this->data['products_moq'];
  }

  function get_max_order_quantity()
  {
    return $this->data['max_order_quantity'];
  }

  function get_order_increment()
  {
    return $this->data['order_increment'];
  }

  function get_unit_class()
  {
    return $this->data['unit_class'];
  }

  function get_price($variants = null, $quantity = 1) {
    //get product price
    $product_price = $this->data['price'];

    //get variant price
    if (is_array($variants) && !empty($variants))
    {
      $product_id_string = osc_get_product_id_string($this->id, $variants);
      if (isset($this->data['variants'][$product_id_string]))
      $product_price = $this->data['variants'][$product_id_string]['price'];
    }
    //if has variant then get default variant price
    else
    {
      if ($this->has_variants())
      {
        if (is_array($this->data['default_variant']) && !empty($this->data['default_variant']))
        {
          $product_price = $this->data['default_variant']['price'];
        }
      }
    }

    $qty_discount = $this->get_quantity_discount($quantity);
    $customer_grp_discount = is_numeric($this->_customer_group_discount) ? $this->_customer_group_discount : 0;

    $product_price = round($product_price * (1 - $qty_discount/100) * (1 - $customer_grp_discount/100), 2);
    return $product_price;
  }

  function get_price_formated($with_special = false)
  {
    global $osC_Services, $osC_Specials, $osC_Currencies;

    $price = '';
    if ($this->is_gift_certificate() && $this->is_open_amount_gift_certificate())
    {
      $price = $osC_Currencies->display_price($this->data['open_amount_min_value'], $this->data['tax_class_id']) . ' ~ ' . $price = $osC_Currencies->displayPrice($this->data['open_amount_max_value'], $this->data['tax_class_id']);;
    }
    else
    {
      if (($with_special === true) && is_object($osC_Services) && $osC_Services->isStarted('specials') && ($new_price = $osC_Specials->getPrice($this->data['id'])))
      {
        $price = '<s>' . $osC_Currencies->displayPrice($this->data['price'], $this->data['tax_class_id']) . '</s> <span class="productSpecialPrice">' . $osC_Currencies->displayPrice($new_price, $this->data['tax_class_id']) . '</span>';
      }
      else
      {
        $price = $osC_Currencies->displayPrice($this->getPrice(), $this->data['tax_class_id']);
      }
    }

    return $price;
  }

  function get_category_id() {
    return $this->data['category_id'];
  }

  function get_images() {
    return $this->data['images'];
  }

  //has problem service如何实现
  function has_special() {
    $special = $this->ci->specials->get_price($this->data['id']);

    if ( is_numeric($special) && ($special > 0) )
    {
      return TRUE;
    }

    return FALSE;
  }

  function has_image() {
    foreach ($this->data['images'] as $image) {
      if ($image['default_flag'] == '1') {
        return TRUE;
      }
    }
  }

  function get_image() {
    $default_image = null;
    $default_variant_image = null;

    foreach ($this->data['images'] as $image) {
      //get variant default image
      if ($this->has_variants()) {
        if (is_array($this->data['default_variant']) && !empty($this->data['default_variant'])){
          if ($image['id'] == $this->data['default_variant']['image']) {
            $default_variant_image = $image['image'];
          }
        }
      }

      //get default image
      if ($image['default_flag'] == '1') {
        $default_image = $image['image'];
      }
    }

    if ($default_variant_image != null) {
      return $default_variant_image;
    }

    return $default_image;
  }

  function has_url() {
    return (isset($this->data['url']) && !empty($this->data['url']));
  }

  function get_url() {
    return $this->data['url'];
  }

  function get_date_available() {
    return $this->data['date_available'];
  }

  function get_date_added() {
    return $this->data['date_added'];
  }

  function get_weight($variants = null){
    if ($variants == null || empty($variants)) {
      return $this->data['products_weight'];
    } else {
      $product_id_string = get_product_id_string($this->get_id(), $variants);

      return $this->data['variants'][$product_id_string]['weight'];
    }
  }

  function get_tax_class_id(){
    return $this->data['tax_class_id'];
  }

  function get_weight_class() {
    return $this->data['products_weight_class'];
  }

  function get_manufacturer() {
    return $this->data['manufacturers_name'];
  }

  function get_quantity_from_db() {
    global $osC_Database;

    if (!isset($this->data['quantity'])) {
      $Qquantity = $osC_Database->query('select products_quantity as quantity from :table_products where products_id = :products_id');
      $Qquantity->bindTable(':table_products', TABLE_PRODUCTS);
      $Qquantity->bindInt(':products_id', $this->data['id']);

      if ($Qquantity->numberOfRows() === 1) {
        $this->data['quantity'] = $Qquantity->value('quantity');
      }
      $Qquantity->freeResult();
    }

    if ( $this->hasVariants() && !isset($this->data['default_variant']['quantity']) ) {
      foreach ($this->data['variants'] as $id => $variant) {
        $Qvariants = $osC_Database->query('select products_quantity as quantity from :table_products_variants where products_variants_id = :products_variants_id');
        $Qvariants->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qvariants->bindInt(':products_variants_id', $variant['variants_id']);
        $Qvariants->execute();

        if ($Qvariants->numberOfRows() === 1) {
          $this->data['variants'][$id]['quantity'] = $Qvariants->value('quantity');

          if ($variant['is_default'] == '1') {
            $this->data['default_variant']['quantity'] = $Qvariants->value('quantity');
          }
        }
      }
    }
  }

  function get_quantity($products_id_string = '') {
    $this->get_quantity_from_db();

    if (is_numeric(strpos($products_id_string,'#'))) {
      if (isset($this->data['variants'][$products_id_string])) {
        return $this->data['variants'][$products_id_string]['quantity'];
      }
    } else {
      $quantity = $this->data['quantity'];

      if (is_array($this->data['default_variant']) && !empty($this->data['default_variant'])) {
        $quantity = $this->data['default_variant']['quantity'];
      }

      return $quantity;
    }
  }

  function has_variants() {
    return (isset($this->data['variants']) && !empty($this->data['variants']));
  }

  function get_variants() {
    $this->get_quantity_from_db();

    return $this->data['variants'];
  }

  function has_customizations() {
    return (isset($this->data['customizations']) && !empty($this->data['customizations']));
  }

  function get_customizations() {
    return $this->data['customizations'];
  }

  function has_required_customization_fields() {
    //    global $toC_Customization_Fields;
    //
    //    foreach ($this->getCustomizations() as $field) {
    //      if ($field['is_required'] == '1') {
    //        return true;
    //      }
    //    }
    //
    //    return false;
  }

  function has_attributes() {
    return (isset($this->data['attributes']) && !empty($this->data['attributes']));
  }

  function get_attributes() {
    return $this->data['attributes'];
  }

  function has_attachments(){
    return (isset($this->data['attachments']) && !empty($this->data['attachments']));
  }

  function get_attachments() {
    return $this->data['attachments'];
  }

  function has_accessories(){
    return (isset($this->data['accessories']) && !empty($this->data['accessories']));
  }

  function get_accessories() {
    return $this->data['accessories'];
  }

  function increment_counter() {
    $this->ci->products_model->increment_counter();
  }

  function number_of_images() {
    return sizeof($this->data['images']);
  }

  //  function renderCustomizationFieldsList() {
  //    global $toC_Customization_Fields;
  //
  //    $output = '<ul>';
  //    foreach ($this->getCustomizations() as $field) {
  //      $tmp = $toC_Customization_Fields->getCustomizationField($this->getID(), $field['customization_fields_id']);
  //      $value = ($tmp === false) ? null : $tmp['customization_value'];
  //
  //      if ($field['type'] == 0) {
  //        $output .= '<li>' . osc_draw_label($field['name'], 'customizations_' . $field['customization_fields_id'], $value, ($field['is_required'] == '1' ? true : false)) . osc_draw_file_field('customizations_' . $field['customization_fields_id'], true) . '<br /><span>' . $value . '</span></li>';
  //      } else {
  //        $output .= '<li>' . osc_draw_label($field['name'], 'customizations[' . $field['customization_fields_id'] . ']', null, ($field['is_required'] == '1' ? true : false)) . osc_draw_input_field('customizations[' . $field['customization_fields_id'] . ']', $value) . '</li>';
  //      }
  //    }
  //    $output .= '</ul>';
  //
  //    return $output;
  //  }

  //  function renderQuantityDiscountTable(){
  //    global $osC_Language;
  //
  //    $output = '<table border="0" cellspacing="0" cellpadding="2" class="productDiscountsTable">' . "\n" .
  //                '<thead>' . "\n" .
  //                '  <tr>' . "\n" .
  //                '    <th>' . lang(table_heading_quantity') . '</th>' . "\n" .
  //                '    <th align="right">' . lang(table_heading_discount') . '</th>' . "\n" .
  //                '  </tr>' . "\n" .
  //                '</thead>' . "\n";
  //
  //    $output .= '<tbody>';
  //    $quantities = array_keys($this->data['quantity_discount']);
  //    for($i = 0; $i < (sizeof($quantities) - 1); $i++){
  //      $output .= '  <tr>' . "\n" .
  //                   '    <td>' . $quantities[$i] . ' ~ ' . ($quantities[$i+1] - 1) . '</td>' . "\n" .
  //                   '    <td align="right">'  . $this->data['quantity_discount'][$quantities[$i]] . '%</td> ' . "\n" .
  //                   '  </tr>' . "\n";
  //    }
  //
  //    $output .= '  <tr>' . "\n" .
  //                 '    <td>' . $quantities[sizeof($quantities) - 1] . '+' . '</td>' . "\n" .
  //                 '    <td align="right">'  . $this->data['quantity_discount'][$quantities[sizeof($quantities) - 1]] . '%</td> ' . "\n" .
  //                 '  </tr>' . "\n";
  //
  //    $output .= '</tbody></table>';
  //
  //    return $output;
  //  }
}
// END Cart Class

/* End of file Cart.php */
/* Location: ./system/libraries/Cart.php */