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
 * @filesource system/library/xml.php
 */ 

class TOC_Xml {
  private $document;
  private $xml_path;
  
  public function __construct() {}
  
/**
 * Load the xml file
 *
 * @access public
 * @param string $xml_path path of the xml file
 * @return Bool
 */
  
  public function load($xml_path) 
  {
    $bad  = array('|//+|', '|\.\./|');
    $good = array('/', '');
    
    $xml_path = preg_replace ($bad, $good, $xml_path);
    
    if (! file_exists ($xml_path)) 
    {
      return FALSE; 
    }
    
    //read the xml file into string
    $this->document = file_get_contents($xml_path);
    $this->xml_path = $xml_path;

    return TRUE;
  }
  
/**
 * parse the xml file and save the result as an array
 *
 * @access public
 * @return Bool
 */
  
  public function parse() 
  {
    $xml = $this->document;
    
    if ($xml == '') 
    {
      return FALSE;
    }

    $doc = new DOMDocument ();
    $doc->preserveWhiteSpace = FALSE;
    
    if ($doc->loadXML($xml)) 
    {
      $array = $this->flatten_node($doc);
      if (count ($array) > 0) 
      {
        return $array;
      }
    }
 
    return FALSE;
  }
  
/**
 * parse the value of one node in the xml
 *
 * @access public
 * 
 * @param string $xml_path the xml need to be modified
 * @param string $node the node need to be modified
 * @param mix $new_value the new value of the node
 * @return Bool
 */
  
  public function set($xml_path,$node,$new_value) 
  {
    $bad  = array('|//+|', '|\.\./|');
    $good = array('/', '');
    $xml_path = preg_replace ($bad, $good, $xml_path);
 
    if (!file_exists($xml_path)) 
    {
      return FALSE;
    }
    else 
    {
      $xml = new DOMDocument();
      $xml->load($xml_path);
            
      if ($xml->getElementsByTagName($node)!= NULL) 
      {
        foreach($xml->getElementsByTagName($node) as $list) 
        {
          $list->nodeValue=$new_value;
          $xml->save($xml_path);
          
          return TRUE;
        }
      }
      else 
      {
        return FALSE;
      }
    }
  }
  
/**
 * get the value of one node in the xml
 *
 * @access public
 * 
 * @param string $xml_path the path of the xml file
 * @param string $node the node need to be got
 * 
 * @return Mixed
 */
  
  public function get($xml_path, $node) {
    $bad  = array('|//+|', '|\.\./|');
    $good = array('/', '');
    $xml_path = preg_replace($bad, $good, $xml_path);

    if (!file_exists($xml_path)) 
    {
      return FALSE;
    }
    else 
    {
      $xml = new DOMDocument();
      $xml->load($xml_path);
      
      if ($xml->getElementsByTagName($node) != NULL) 
      {
        foreach($xml->getElementsByTagName($node) as $list) 
        {
          return $list->nodeValue;
        }
      }
      else 
      {
        return FALSE;
      }
    }
  }
  
/**
 * parse the xml file as an array
 *
 * @access private
 * 
 * @param object $node the xml doc
 * @return Array
 */
  
  private function flatten_node($node) {
    $array = array();
 
    foreach ($node->childNodes as $child) 
    {
      if ($child->hasChildNodes()) 
      {
        if ($node->firstChild->nodeName == $node->lastChild->nodeName && $node->childNodes->length > 1) 
        {
          $array[$child->nodeName][] = $this->flatten_node($child);
        }
        else 
        {
          $array[$child->nodeName][] = $this->flatten_node($child);
 
          if ($child->hasAttributes()) 
          {
            $index = count($array[$child->nodeName]) - 1;
            $attrs = &$array[$child->nodeName][$index]['__attrs'];
            
            foreach($child->attributes as $attribute) 
            {
              $attrs[$attribute->name] = $attribute->value;
            }
          }
        }
      }
      else 
      {
        return $child->nodeValue;
      }
    }
    
    return $array;
  }
  
  public function put_header($filename, $content_length) {
    header('Content-Description: File Transfer');
    header('Content-disposition: attachment; filename=' . $filename . '.xml');
    header('Content-Type: text/xml');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . $content_length);
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');      
  }
  
  public function get_xml($root, $data = NULL) {
    // $data必须是数组
    if ($data == NULL || !is_array($data) || count($data) == 0) 
    {
      return FALSE;
    }
    
    // 生成DOM对象
    $dom = new DOMDocument('1.0','utf-8');
    
    $dom->formatOutput = true;
    

    // 创建DOM根元素
    $root = $dom->createElement($root);

    // 循环组织DOM元素
    $this->struct_dom($dom, $data, $root);

    // 将创建完成的DOM元素加入DOM对象
    $dom->appendChild($root);
    
    $xml = $dom->saveXML();
    
    return $xml;
  }
  
  private function struct_dom($dom, $data, $root) 
  {
    if (is_array($data)) 
    {
      // 因为XML节点名不能为纯数字，所以这里需要进行一下判断
      foreach ($data as $key => $value) 
      {
        if (is_numeric($key))
        {
          $key_element = $dom->createElement('definition');
          $this->struct_dom($dom, $value, $key_element);
          
          $root->appendChild($key_element);
        }
        else
        {
          $key_element = $dom->createElement($key);
            // 递归转换为XML
          if (is_array($value)) 
          {
            $root->appendChild($key_element);
            $this->struct_dom($dom, $value, $key_element);
          } 
          else 
          {
            $key_element->appendChild($dom->createTextNode($value));
            $root->appendChild($key_element);
          }
        }
      }
      
      return $root;
    }
    
    return FALSE;
  }
}

/* End of file xml.php */
/* Location: /system/library/xml.php */