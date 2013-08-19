<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * TomatoCart General Helpers
 *
 * @package		TomatoCart
 * @subpackage	Helpers
 * @category	Helpers
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

/**
 * Gets a translation
 *
 * @access public
 * @param $key The key to the translation
 * @return string translation
 */
if( ! function_exists('lang'))
{
    function lang($key)
    {
        $CI =& get_instance();

        return $CI->lang->line($key);
    }
}

/**
 * Gets all languages
 *
 * @access public
 * @return array languages
 */
if( ! function_exists('lang_code'))
{
    function lang_code()
    {
        $CI =& get_instance();

        return $CI->lang->get_code();
    }
}

/**
 * Gets all languages
 *
 * @access public
 * @return array languages
 */
if( ! function_exists('get_languages'))
{
    function get_languages()
    {
        $CI =& get_instance();

        return $CI->lang->get_languages();
    }
}

/**
 * Display language flag
 *
 * @access public
 * @param $code language code
 * @return array languages
 */
if( ! function_exists('get_language_flag'))
{
    function get_language_flag($code)
    {
        $flag = strtolower(substr($code, 3));

        return store_url() . '/images/worldflags/' . $flag . '.png';
    }
}

/**
 * Get store url
 *
 * @access public
 * @return string
 */
if( ! function_exists('store_url'))
{
    function store_url()
    {
        return trim(base_url(), 'install/');
    }
}

/**
 * Encrypt password
 *
 * @access public
 * @param $plian
 * @return string
 */
if( ! function_exists('encrypt_string'))
{
    function encrypt_string($plain)
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

/**
 * Traverse recursively directory and return files
 * 
 * @access public
 * @param $path
 * @return array
 */
function traverse_hierarchy($path)
{
    $return_array = array();

    $dir = opendir($path);
    while(($file = readdir($dir)) !== false)
    {
        if($file[0] == '.') continue;

        $fullpath = $path . '/' . $file;
        if(is_dir($fullpath))
        $return_array = array_merge($return_array, traverse_hierarchy($fullpath));
        else // your if goes here: if(substr($file, -3) == "jpg") or something like that
        $return_array[] = $fullpath;
    }

    return $return_array;
}

/**
 * Copy complete directroy
 * 
 * @access public
 * @param $source
 * @param $target
 * @return void
 */
function toc_copy($source, $target) {
    if (is_dir($source)) {
        $src_dir = dir($source);

        while ( false !== ($file = $src_dir->read()) ) {
            if ($file == '.' || $file == '..' || $file == '.svn' || $file == '.gitignore' || $file == '.gitkeep') {
                continue;
            }

            $src_file = $source . '/' . $file;
            if (is_dir($src_file)) {
                toc_copy($src_file, $target . '/' . $file );
                continue;
            }
            copy( $src_file, $target . '/' . $file );
        }

        $src_dir->close();
    }else {
        copy($source, $target);
    }
}

/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access  public
 * @param string  path to source
 * @param int   depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return  array
 */
if ( ! function_exists('directory_map'))
{
    function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata = array();
            $new_depth  = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
                {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
                {
                    $filedata[$file] = directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }
}

// ------------------------------------------------------------------------

/**
 * Resize image
 *
 * @access public
 * @param $original_image original image
 * @param $dest_image dest image
 * @param $dest_width dest width
 * @param $dest_height dest height
 * @param $force_size force resize
 * @return boolean
 */
if( ! function_exists('toc_gd_resize'))
{
    function toc_gd_resize($original_image, $dest_image, $dest_width, $dest_height, $force_size = '0') {
        $img_type = NULL;

        switch (strtolower(substr(basename($original_image), (strrpos(basename($original_image), '.')+1))))
        {
            case 'jpg':
            case 'jpeg':
                if (imagetypes() & IMG_JPG) {
                    $img_type = 'jpg';
                }

                break;

            case 'gif':
                if (imagetypes() & IMG_GIF) {
                    $img_type = 'gif';
                }

                break;

            case 'png':
                if (imagetypes() & IMG_PNG) {
                    $img_type = 'png';
                }

                break;
        }

        if ($img_type !== NULL)
        {
            list($orig_width, $orig_height) = getimagesize($original_image);

            $width  = $dest_width;
            $height = $dest_height;

            $factor = max(($orig_width / $width), ($orig_height / $height));

            if ($force_size == '1') {
                $width = $dest_width;
            } else {
                $width  = round($orig_width / $factor);
                $height = round($orig_height / $factor);
            }

            $im_p = @imagecreatetruecolor($dest_width, $dest_height);
            @imagealphablending($im_p, true);
            $color = @imagecolortransparent($im_p, imagecolorallocatealpha($im_p, 255, 255, 255, 127));
            @imagefill($im_p, 0, 0, $color);
            @imagesavealpha($im_p, true);

            $x = 0;
            $y = 0;

            if ($force_size == '1') {
                $width = round($orig_width * $dest_height / $orig_height);

                if ($width < $dest_width) {
                    $x = floor(($dest_width - $width) / 2);
                }
            } else {
                $x = floor(($dest_width - $width) / 2);
                $y = floor(($dest_height - $height) / 2);
            }

            switch ($img_type) {
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

            switch ($img_type) {
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
        } else {
            return FALSE;
        }
    }
}

/* End of file general_helper.php */
/* Location: ./install/helpers/toc_general_helper.php */
