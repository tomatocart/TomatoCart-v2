<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Directory Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------

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


/**
 * Create a Directory Make
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse
 * @return	bool
 */
if ( ! function_exists('directory_make'))
{
    function directory_make($path, $permission = 0755)
    {
        if (!file_exists($path)) {
            mkdir($path, $permission);
        }

        if (file_exists($path)) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('dircopy'))
{
    function dircopy($src, $dest, $folder_permission = 0755, $file_permission = 0644) {
        $res = false;
         
        $src = str_replace('\\', '/', $src);
        $src = str_replace('//', '/', $src);
        $dest = str_replace('\\', '/', $dest);
        $dest = str_replace('//', '/', $dest);

        //file copy
        if ( is_file($src) ) {
            if(is_dir($dest)) {
                if ($dest[ strlen($dest)-1 ] != '/') {
                    $__dest = $dest . "/";
                }

                $__dest .= basename($src);
            } else {
                $__dest = $dest;
            }

            $res = copy($src, $__dest);

            chmod($__dest, $file_permission);
        }
        //directory copy
        elseif ( is_dir($src) ) {
            if ( !is_dir($dest) ) {
                directory_make($dest, $folder_permission);
                chmod($dest, $folder_permission);
            }

            if ( $src[strlen($src)-1] != '/') {
                $__src = $src . '/';
            } else {
                $__src = $src;
            }

            if ($dest[strlen($dest) - 1]!='/') {
                $__dest = $dest . '/';
            } else {
                $__dest = $dest;
            }

            $res = true;
            $handle = opendir($src);
            while ( $file = readdir($handle) ) {
                if($file != '.' && $file != '..') {
                    $res = dircopy($__src . $file, $__dest . $file, $folder_permission, $file_permission);
                }
            }

            closedir($handle);
        } else {
            $res = false;
        }

        return $res;
    }
}



/* End of file directory_helper.php */
/* Location: ./system/helpers/directory_helper.php */