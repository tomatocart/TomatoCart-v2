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
 * TOC Loader
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
*/
class TOC_Loader extends CI_Loader {
	
	/**
	 * Constructor
	 *
	 * Sets the path to the view files and gets the initial output buffering level
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		
		/**
		 * Add the library path of the store front and library path of local into the _ci_library_paths
		 * When load libary in the controller, the sytem will search the class in current app path, and then in the ci library.
		 * If the class isn't searched, the libary directory of the store front and of local application will be searched separately.
		 * 
		 * The FRONTPATH constant is pointed to ../system/tomatocart path.
		 * The LOCALAPPPAH constant is pointed to admin/local path.
		 * Both fo them are defined in the admin/index.php
		 */
		array_push($this->_ci_library_paths, FRONTPATH, LOCALAPPPATH);
		
	}

	// --------------------------------------------------------------------

	/**
	 * Override parent _ci_load to support public invoke of _ci_load function.
	 *
	 * @param array $_ci_data
	 */
	public function _ci_load($_ci_data)
	{
		return parent::_ci_load($_ci_data);
	}

	// --------------------------------------------------------------------

	/**
	 * Class Loader
	 *
	 * CI library loader require $params to be an array, so we override the library loader to support simple type for $param
	 *
	 * @param	string	the name of the class
	 * @param	mixed	the optional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	public function library($library = '', $params = NULL, $object_name = NULL, $extend = FALSE)
	{
		if (is_array($library))
		{
			foreach ($library as $class)
			{
				$this->library($class, $params);
			}

			return;
		}

		if ($library === '' OR isset($this->_base_classes[$library]))
		{
			return FALSE;
		}

		$this->_ci_load_class($library, $params, $object_name, $extend);
	}

	// --------------------------------------------------------------------

	/**
	 * Load class
	 *
	 * This function loads the requested class.
	 *
	 * @param	string	the item that is being loaded
	 * @param	mixed	any additional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	protected function _ci_load_class($class, $params = NULL, $object_name = NULL, $extend = FALSE)
	{
		// Get the class name, and while we're at it trim any slashes.
		// The directory path can be included as part of the class name,
		// but we don't want a leading slash
		$class = str_replace('.php', '', trim($class, '/'));

		// Was the path included with the class name?
		// We look for a slash to determine this
		$subdir = '';
		if (($last_slash = strrpos($class, '/')) !== FALSE)
		{
			// Extract the path
			$subdir = substr($class, 0, ++$last_slash);

			// Get the filename from the path
			$class = substr($class, $last_slash);
		}

		// We'll test for both lowercase and capitalized versions of the file name
		foreach (array(ucfirst($class), strtolower($class)) as $class)
		{
			/**
			 * Support: the local sub-classes extened from toc core classed of the admin application
			 * 
			 * For example, you could extend the TOC_Customers under admin > system > libraries
			 * You just need to add a config_item('local_subclass_prefix').customers file under admin > local >libraries and then define the class in it
			 * You could define any local sublcass prefix in local/config.php
			 * 
			 * If the toc core class is extended from ci core class, it will also works as expected
			 */
			$local_subclass = LOCALAPPPATH . 'libraries/'.$subdir.config_item('local_subclass_prefix').$class.'.php';

			// Whether it is a local class extension request
			if (file_exists($local_subclass))
			{
				// Safety: Was the class already loaded by a previous call?
				if (in_array($local_subclass, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied. If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, config_item('local_subclass_prefix'), $params, $object_name);
						}
					}
				
					$is_duplicate = TRUE;
					log_message('debug', $class.' class already loaded. Second attempt ignored.');
					return;
				}

				// extend the core tomatocart class which is also a sub-class of the ci core class
				$subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';

				if (file_exists($subclass))
				{
					$baseclass = BASEPATH.'libraries/'.ucfirst($class).'.php';

					if ( ! file_exists($baseclass))
					{
						log_message('error', 'Unable to load the requested class: '.$class);
						show_error('Unable to load the requested class: '.$class);
					}

					include_once($baseclass);
					include_once($subclass);
					include_once($local_subclass);
					$this->_ci_loaded_files[] = $local_subclass;

					return $this->_ci_init_class($class, config_item('local_subclass_prefix'), $params, $object_name);
				}

				//just extend from core tomatocart base class
				$base_class = APPPATH.'libraries/'.$subdir.$class.'.php';
				if ( ! file_exists($base_class))
				{
					log_message('error', 'Unable to load the requested class: '.$class);
					show_error('Unable to load the requested class: '.$class);
				}
				
				include_once($baseclass);
				include_once($local_subclass);
				$this->_ci_loaded_files[] = $local_subclass;
				
				return $this->_ci_init_class($class, config_item('local_subclass_prefix'), $params, $object_name);
			}

			/**
			 * Support: tomatocart core class extend from another core class
			 *
			 * For example: we have toc_customers class in the tomatocart core libraries. You wish to create a sub-class from toc_customers
			 * In this case, you just need to create a file named config_item('toc_subclass_prefix').customers.php
			 * 
			 * You have to pass the param - extend as true into load->libarary to create the sub-class instance directly
			 * 
			 * If the parent class is extend from ci core class, it will still work
			 * 
			 */
			if ($extend === TRUE)
			{
				$toc_subclass = APPPATH.'libraries/'.$subdir.config_item('toc_subclass_prefix').$class.'.php';
				
				// Safety: Was the class already loaded by a previous call?
				if (in_array($toc_subclass, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied. If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, config_item('toc_subclass_prefix'), $params, $object_name);
						}
					}
				
					$is_duplicate = TRUE;
					log_message('debug', $class.' class already loaded. Second attempt ignored.');
					return;
				}

				if (file_exists($toc_subclass))
				{
					//the parent class might be extended from core ci class
					$toc_sub_baseclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';

					if (file_exists($toc_sub_baseclass))
					{
						$baseclass = BASEPATH.'libraries/'.ucfirst($class).'.php';

						if ( ! file_exists($baseclass))
						{
							log_message('error', 'Unable to load the requested class: '.$class);
							show_error('Unable to load the requested class: '.$class);
						}

						include_once($baseclass);
						include_once($toc_sub_baseclass);
						include_once($toc_subclass);
						$this->_ci_loaded_files[] = $toc_subclass;

						return $this->_ci_init_class($class, config_item('toc_subclass_prefix'), $params, $object_name);
					}

					//the norml parent class
					$toc_baseclass = APPPATH.'libraries/'.$subdir.$class.'.php';

					if ( ! file_exists($toc_baseclass))
					{
						log_message('error', 'Unable to load the requested class: '.$class);
						show_error('Unable to load the requested class: '.$class);
					}

					include_once($toc_baseclass);
					include_once($toc_subclass);
					$this->_ci_loaded_files[] = $toc_subclass;

					return $this->_ci_init_class($class, config_item('toc_subclass_prefix'), $params, $object_name);
				}
			}
				
			//Just load a TomatoCart core class which is extended from core ci class
			$subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';

			// Is this a class extension request?
			if (file_exists($subclass))
			{
				$baseclass = BASEPATH.'libraries/'.ucfirst($class).'.php';

				if ( ! file_exists($baseclass))
				{
					log_message('error', 'Unable to load the requested class: '.$class);
					show_error('Unable to load the requested class: '.$class);
				}

				// Safety: Was the class already loaded by a previous call?
				if (in_array($subclass, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied. If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
						}
					}

					$is_duplicate = TRUE;
					log_message('debug', $class.' class already loaded. Second attempt ignored.');
					return;
				}

				include_once($baseclass);
				include_once($subclass);
				$this->_ci_loaded_files[] = $subclass;

				return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
			}

			// Lets search for the requested library file and load it.
			$is_duplicate = FALSE;
			foreach ($this->_ci_library_paths as $path)
			{
				$filepath = $path.'libraries/'.$subdir.$class.'.php';

				// Does the file exist? No? Bummer...
				if ( ! file_exists($filepath))
				{
					continue;
				}

				// Safety: Was the class already loaded by a previous call?
				if (in_array($filepath, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied. If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, '', $params, $object_name);
						}
					}

					$is_duplicate = TRUE;
					log_message('debug', $class.' class already loaded. Second attempt ignored.');
					return;
				}

				include_once($filepath);
				$this->_ci_loaded_files[] = $filepath;
				return $this->_ci_init_class($class, '', $params, $object_name);
			}

		} // END FOREACH

		// One last attempt. Maybe the library is in a subdirectory, but it wasn't specified?
		if ($subdir === '')
		{
			$path = strtolower($class).'/'.$class;
			return $this->_ci_load_class($path, $params);
		}
		
		// If we got this far we were unable to find the requested class.
		// We do not issue errors if the load call failed due to a duplicate request
		if ($is_duplicate === FALSE)
		{
			log_message('error', 'Unable to load the requested class: '.$class);
			show_error('Unable to load the requested class: '.$class);
		}
	}
}
/* End of file TOC_Loader.php */
/* Location: ./system/tomatocart/core/TOC_Loader.php */