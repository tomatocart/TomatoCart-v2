<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Settings Model
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	Admin settings
 * @author		Ionize Dev Team
 */

class Extensions_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function save($group, $module, $params)
    {
        $this->db->where('modules_group', $group)->where('code', $module);
        return $this->db->update('extensions', array('params' => $params));
    }

    /**
     * Get the installed modules of the specified groups
     *
     * @return	The modules
     */
    function get_modules($group)
    {
        $result = $this->db->select('code')->from('extensions')->where('modules_group', $group)->get();

        $modules = FALSE;
        foreach($result->result_array() as $row)
        {
            $modules[] = $row['code'];
        }

        return $modules;
    }

    /**
     * Get the installed modules of the specified groups
     *
     * @return	The modules
     */
    function get_module($group, $code)
    {
        $result = $this->db->select('*')->from('extensions')->where('modules_group', $group)->where('code', $code)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            return $data;
        }

        return NULL;
    }

    /**
     * Get the installed modules of the specified groups
     *
     * @return	The modules
     */
    function install($data)
    {
        return $this->db->insert('extensions', $data);
    }

    /**
     * Get the installed modules of the specified groups
     *
     * @return	The modules
     */
    function uninstall($group, $module)
    {
        return $this->db->delete('extensions', array('modules_group' => $group, 'code' => $module));
    }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */