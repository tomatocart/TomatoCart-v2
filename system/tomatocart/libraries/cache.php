<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Cache Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Cache
{
    /**
     * Cached data
     *
     * @access protected
     * @var array
     */
    protected $cached_data;

    /**
     * Cache key name
     *
     * @access protected
     * @var string
     */
    protected $cache_key;

    /**
     * Write data to key file
     *
     * @access public
     * @param $key
     * @param $data
     */
    public function write($key, &$data)
    {
        $filename = DIR_FS_WORK . $key . '.cache';

        if ($fp = @fopen($filename, 'w'))
        {
            flock($fp, 2); // LOCK_EX
            fputs($fp, serialize($data));
            flock($fp, 3); // LOCK_UN
            fclose($fp);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Read data from key file
     *
     * @access public
     * @param $key
     * @param $expire
     */
    public function read($key, $expire = 0)
    {
        $this->cache_key = $key;

        $filename = DIR_FS_WORK . $key . '.cache';

        if (file_exists($filename))
        {
            $difference = floor((time() - filemtime($filename)) / 60);

            if ( ($expire == '0') || ($difference < $expire) )
            {
                if ($fp = @fopen($filename, 'r'))
                {
                    $this->cached_data = unserialize(fread($fp, filesize($filename)));

                    fclose($fp);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get cache data
     * 
     * @access public
     * @return array
     */
    public function &getCache()
    {
        return $this->cached_data;
    }

    /**
     * Start buffer data
     * 
     * @access public
     * @return void
     */
    public function startBuffer()
    {
        ob_start();
    }
    
    /**
     * Stop Buffer data
     * 
     * @access public
     * @return void
     * 
     */
    public function stopBuffer()
    {
        $this->cached_data = ob_get_contents();

        ob_end_clean();

        $this->write($this->cache_key, $this->cached_data);
    }

    /**
     * Write data to buffer
     * 
     * @access public
     * @param $data
     * @return void
     */
    public function writeBuffer(&$data)
    {
        $this->cached_data = $data;

        $this->write($this->cache_key, $this->cached_data);
    }

    /**
     * Remove cached file
     * 
     * @access public
     * @param $key
     * @return void
     */
    public function clear($key)
    {
        $key_length = strlen($key);

        $d = dir(DIR_FS_WORK);

        while ($entry = $d->read())
        {
            if ((strlen($entry) >= $key_length) && (substr($entry, 0, $key_length) == $key))
            {
                @unlink(DIR_FS_WORK . $entry);
            }
        }

        $d->close();
    }
}
// END Cache Class

/* End of file cache.php */
/* Location: ./system/tomatocart/library/cache.php */