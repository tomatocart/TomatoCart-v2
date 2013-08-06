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
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Directory Listing Library
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  library
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class TOC_Directory_Listing 
{

/* Protected*/

    private $_directory = '';
    private $_include_files = TRUE;
    private $_include_directories = TRUE;
    private $_exclude_entries = array('.', '..','.svn');
    private $_stats = FALSE;
    private $_recursive = FALSE;
    private $_check_extension = array();
    private $_add_directory_to_filename = FALSE;
    private $_listing;

/* Class constructor */

    public function __construct($config)
    {
        if (!empty($config))
        {
            $this->set_directory(realpath($config['directory']));
            $this->set_stats($config['stats']);
        }
    }
    
/* Public methods */
    
    public function set_directory($directory) 
    {
        $this->_directory = $directory;
    }

    public function set_include_files($boolean) 
    {
        if ($boolean === TRUE) 
        {
            $this->_include_files = TRUE;
        } 
        else 
        {
            $this->_include_files = FALSE;
        }
    }

    public function set_include_directories($boolean) 
    {
        if ($boolean === TRUE) 
        {
            $this->_include_directories = TRUE;
        } 
        else 
        {
            $this->_include_directories = FALSE;
        }
    }

    public function set_exclude_entries($entries)
    {
        if (is_array($entries))
        {
            foreach ($entries as $value)
            {
                if (!in_array($value, $this->_exclude_entries))
                {
                    $this->_exclude_entries[] = $value;
                }
            }
        }
        elseif (is_string($entries)) 
        {
            if (!in_array($entries, $this->_exclude_entries))
            {
                $this->_exclude_entries[] = $entries;
            }
        }
    }

    public function set_stats($boolean) 
    {
        if ($boolean === TRUE)
        {
            $this->_stats = TRUE;
        } 
        else
        {
            $this->_stats = FALSE;
        }
    }

    public function set_recursive($boolean)
    {
      if ($boolean === TRUE)
      {
          $this->_recursive = TRUE;
      } 
      else
      {
          $this->_recursive = FALSE;
      }
    }

    public function set_check_extension($extension) 
    {
        $this->_check_extension[] = $extension;
    }

    public function set_add_directory_to_filename($boolean)
    {
        if ($boolean === TRUE)
        {
            $this->_add_directory_to_filename = TRUE;
        } 
        else
        {
            $this->_add_directory_to_filename = FALSE;
        }
    }

    public function read($directory = '') 
    {
        if (empty($directory)) 
        {
            $directory = $this->_directory;
        }
  
        if (!is_array($this->_listing))
        {
            $this->_listing = array();
        }
  
        if ($dir = @dir($directory)) 
        {
            while (($entry = $dir->read()) !== FALSE) 
            {
                if (!in_array($entry, $this->_exclude_entries)) 
                {
                    if (($this->_include_files === TRUE) && is_file($dir->path . '/' . $entry)) 
                    {
                        if (empty($this->_check_extension) || in_array(substr($entry, strrpos($entry, '.')+1), $this->_check_extension)) 
                        {
                            if ($this->_add_directory_to_filename === TRUE) 
                            {
                                if ($dir->path != $this->_directory) 
                                {
                                    $entry = substr($dir->path, strlen($this->_directory)+1) . '/' . $entry;
                                }
                            }
            
                            $this->_listing[] = array('name' => $entry,
                                                      'is_directory' => FALSE);
                            if ($this->_stats === TRUE) 
                            {
                                $stats = array('size' => filesize($dir->path . '/' . $entry),
                                               'permissions' => fileperms($dir->path . '/' . $entry),
                                               'user_id' => fileowner($dir->path . '/' . $entry),
                                               'group_id' => filegroup($dir->path . '/' . $entry),
                                               'last_modified' => filemtime($dir->path . '/' . $entry));
                                $this->_listing[sizeof($this->_listing)-1] = array_merge($this->_listing[sizeof($this->_listing)-1], $stats);
                            }
                        }
                    } 
                    elseif (is_dir($dir->path . '/' . $entry))
                    {
                        if ($this->_include_directories === TRUE)
                        {
                            $entry_name= $entry;
            
                            if ($this->_add_directory_to_filename === TRUE)
                            {
                                if ($dir->path != $this->_directory)
                                {
                                    $entry_name = substr($dir->path, strlen($this->_directory)+1) . '/' . $entry;
                                }
                            }
            
                            $this->_listing[] = array('name' => $entry_name,
                                                      'is_directory' => TRUE);
                            
                            if ($this->_stats === TRUE) 
                            {
                                $stats = array('size' => filesize($dir->path . '/' . $entry),
                                               'permissions' => fileperms($dir->path . '/' . $entry),
                                               'user_id' => fileowner($dir->path . '/' . $entry),
                                               'group_id' => filegroup($dir->path . '/' . $entry),
                                               'last_modified' => filemtime($dir->path . '/' . $entry));
                                $this->_listing[sizeof($this->_listing)-1] = array_merge($this->_listing[sizeof($this->_listing)-1], $stats);
                            }
                        }
          
                        if ($this->_recursive === TRUE)
                        {
                            $this->read($dir->path . '/' . $entry);
                        }
                    }
                }
            }
    
            $dir->close();
            unset($dir);
        }
    }

    public function get_files($sort_by_directories = TRUE) 
    {
        if (!is_array($this->_listing))
        {
            $this->read();
        }
  
        if (is_array($this->_listing) && (sizeof($this->_listing) > 0))
        {
            if ($sort_by_directories === TRUE)
            {
                usort($this->_listing, array($this, '_sortListing'));
            }
    
            return $this->_listing;
        }

        return array();
    }

    public function get_size()
    {
        if (!is_array($this->_listing))
        {
            $this->read();
        }
  
        return sizeof($this->_listing);
    }

    public function get_directory()
    {
        return $this->_directory;
    }

/* Private methods */

    private function _sortListing($a, $b)
    {
        return strcmp((($a['is_directory'] === TRUE) ? 'D' : 'F') . $a['name'], (($b['is_directory'] === TRUE) ? 'D' : 'F') . $b['name']);
    }
}

/* End of file directory_listing.php */
/* Location: ./system/libraries/directory_listing.php */