<?php
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 * 
 * @package     TomatoCart
 * @author      TomatoCart Dev Team
 * @copyright   Copyright (c) 2009 - 2013, TomatoCart. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html
 * @link        http://tomatocart.com
 * @since       2.0.0
 * @filesource  
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * TomatoCart Language Helpers
 * 
 * @package     TomatoCart
 * @subpackage  Helpers
 * @category    Helpers
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com
 */

// ------------------------------------------------------------------------

if ( ! function_exists('config'))
{
    /**
     * Returns the specified config item
     * 
     * @param   string $key
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function config($key)
    {
        return ci()->configuration->line($key);
    }
}

if ( ! function_exists('encrypt_password'))
{
    /**
     * Encrypt password
     * 
     * @param   string $plain
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function encrypt_password($plain)
    {
        $password = '';

        for ($i=0; $i<10; $i++)
        {
            $password .= mt_rand();
        }

        $salt = substr(md5($password), 0, 2);

        $password = md5($salt . $plain) . ':' . $salt;

        return $password;
    }
}

if ( ! function_exists('get_product_id_string'))
{
    /**
     * Generate a product ID string value containing its product variants combinations
     * 
     * @param   integer $id     The product ID
     * @param   array   $params An array of product variants
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function get_product_id_string($id, $params)
    {
        $string = (int)$id;

        if (is_array($params) && ! empty($params))
        {
            $variants_check = TRUE;
            $variants_ids = array();

            ksort($params);

            foreach ($params as $group => $value)
            {
                if (is_numeric($group) && is_numeric($value))
                {
                    $variants_ids[] = (int)$group . ':' . (int)$value;
                }
                else
                {
                    $variants_check = FALSE;
                    break;
                }
            }

            if ($variants_check === TRUE)
            {
                $string .= '#' . implode(';', $variants_ids);
            }
        }

        return $string;
    }
}

if ( ! function_exists('call_config_func'))
{
    /**
     * Call a function given in string format used by configuration set and use functions
     * 
     * @param   string  $function
     * @param   string  $default
     * @param   string  $key
     * 
     * @return  mixed
     * 
     * @since   2.0.0
     */
    function call_config_func($function, $default = NULL, $key = NULL)
    {
        if (strpos($function, '::') !== FALSE)
        {
            $class_method = explode('::', $function);
            $class = str_replace('toc_', '', strtolower($class_method[0]));

            ci()->load->library($class);

            return call_user_func(array(ci()->$class, $class_method[1]), $default, $key);
        }
        else
        {
            $function_name = $function;
            $function_parameter = '';

            if (strpos($function, '(') !== FALSE)
            {
                $function_array = explode('(', $function, 2);

                $function_name = $function_array[0];
                $function_parameter = substr($function_array[1], 0, -1);
            }

            if ( ! function_exists($function_name))
            {
                ci()->load->helper('cfg_parameters/' . $function_name);
            }

            if ( ! empty($function_parameter))
            {
                return call_user_func($function_name, $function_parameter, $default, $key);
            }
            else
            {
                return call_user_func($function_name, $default, $key);
            }
        }
    }
}

if ( ! function_exists('get_ip_address'))
{
    /**
     * Get the IP address of the client
     * 
     * @return  string
     * 
     * @since   2.0.0
     */
    function get_ip_address()
    {
        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $ip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $ip = getenv('REMOTE_ADDR');
            }
        }

        return $ip;
    }
}

if ( ! function_exists('store_front_path'))
{
    /**
     * Return store front path
     * 
     * @return  string  store front path
     * 
     * @since   2.0.0
     */
    function store_front_path()
    {
        return realpath(APPPATH . '../../') . '/';
    }
}

if ( ! function_exists('load_front'))
{
    /**
     * Load front class, library, helper, etc
     * 
     * @param   string $class
     * @param   string $type
     * 
     * @return  boolean
     * 
     * @since   2.0.0
     */
    function load_front($class, $type = 'libraries')
    {
        $path = store_front_path() . 'system/tomatocart/' . $type . '/' . $class;

        if (file_exists($path))
        {
            require_once $path;

            return TRUE;
        }

        return FALSE;
    }
}

if ( ! function_exists('load_front_library'))
{
    /**
     * Return store front path
     * 
     * @param   string $library
     * 
     * @return  string  store front path
     * 
     * @since   2.0.0
     */
    function load_front_library($library)
    {
        return load_front($library);
    }
}

if ( ! function_exists('toc_gd_resize'))
{
    /**
     * Resize image
     * 
     * @param   string  $original_image Original image
     * @param   string  $dest_image     Dest image
     * @param   integer $dest_width     Dest width
     * @param   integer $dest_height    Dest height
     * @param   integer $force_size     Optional force resize
     * 
     * @return  boolean TRUE on success or FALSE on failure.
     * 
     * @since   2.0.0
     */
    function toc_gd_resize($original_image, $dest_image, $dest_width, $dest_height, $force_size = '0')
    {
        $img_type = NULL;

        switch (strtolower(substr(basename($original_image), (strrpos(basename($original_image), '.')+1))))
        {
            case 'jpg':
            case 'jpeg':
                if (imagetypes() & IMG_JPG)
                {
                    $img_type = 'jpg';
                }

                break;
            case 'gif':
                if (imagetypes() & IMG_GIF)
                {
                    $img_type = 'gif';
                }

                break;
            case 'png':
                if (imagetypes() & IMG_PNG)
                {
                    $img_type = 'png';
                }

                break;
        }

        if ($img_type !== NULL)
        {
            list($orig_width, $orig_height) = getimagesize($original_image);

            $width = $dest_width;
            $height = $dest_height;

            $factor = max(($orig_width / $width), ($orig_height / $height));

            if ($force_size === '1')
            {
                $width = $dest_width;
            }
            else
            {
                $width = round($orig_width / $factor);
                $height = round($orig_height / $factor);
            }

            $im_p = @imagecreatetruecolor($dest_width, $dest_height);
            @imagealphablending($im_p, TRUE);
            $color = @imagecolortransparent($im_p, imagecolorallocatealpha($im_p, 255, 255, 255, 127));
            @imagefill($im_p, 0, 0, $color);
            @imagesavealpha($im_p, TRUE);

            $x = 0;
            $y = 0;

            if ($force_size === '1')
            {
                $width = round($orig_width * $dest_height / $orig_height);

                if ($width < $dest_width)
                {
                    $x = floor(($dest_width - $width) / 2);
                }
            }
            else
            {
                $x = floor(($dest_width - $width) / 2);
                $y = floor(($dest_height - $height) / 2);
            }

            switch ($img_type)
            {
                case 'jpg':
                    $im = @imagecreatefromjpeg($original_image);
                    break;
                case 'gif':
                    $im = @imagecreatefromgif($original_image);
                    break;
                case 'png':
                    $im = @imagecreatefrompng($original_image);
                    break;
            }

            @imagecopyresampled($im_p, $im, $x, $y, 0, 0, $width, $height, $orig_width, $orig_height);

            switch ($img_type)
            {
                case 'jpg':
                    @imagejpeg($im_p, $dest_image);
                    break;
                case 'gif':
                    @imagegif($im_p, $dest_image);
                    break;
                case 'png':
                    @imagepng($im_p, $dest_image);
                    break;
            }

            @imagedestroy($im_p);
            @imagedestroy($im);

            @chmod($dest_image, 0777);

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

/* End of file core_helper.php */
/* Location: ./admin/system/helpers/core_helper.php */