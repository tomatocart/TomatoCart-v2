<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Administrators Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */
class Administrators_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
      
    // ------------------------------------------------------------------------

    /**
     * Get the administrators
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_administrators($start = NULL, $limit = NULL)
    {
        $this->db
            ->select('id, user_name, email_address')
            ->from('administrators')
            ->order_by('user_name');
        
        if ($start !== NULL && $limit !== NULL)
        {
            $this->db->limit($limit, $start);
        }
        
        $result = $this->db->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Save the administrator
     *
     * @access public
     * @param $id
     * @param $data
     * @param $modules
     * @return boolean
     */
    public function save($id = NULL, $data, $modules = NULL)
    {
        $this->load->helper('email');
        
        $error = FALSE;

        //validate email address
        if (valid_email($data['email_address']))
        {
            $this->db
                ->select('id')
                ->from('administrators')
                ->where('email_address', $data['email_address']);

            if (is_numeric($id))
            {
                $this->db->where('id !=', $id);
            }

            $result = $this->db->get();

            //verify that the email address has already been used
            if ($result->num_rows() > 0)
            {
                return -4;
            }
        }
        else
        {
            return -3;
        }

        //verify that the username has already been used
        $this->db->select('id')->from('administrators')->where('user_name', $data['username']);

        if (is_numeric($id))
        {
            $this->db->where('id !=', $id);
        }

        $result = $this->db->limit(1)->get();

        if ($result->num_rows() == 1)
        {
            return -2;
        }

        //start transaction
        $this->db->trans_begin();

        $admin_data = array('user_name' => $data['username'], 'email_address' => $data['email_address']);

        //editing or adding the administrator
        if (is_numeric($id))
        {
            if (isset($data['password']) && !empty($data['password']))
            {
                $admin_data['user_password'] = encrypt_password(trim($data['password']));
            }

            $this->db->update('administrators', $admin_data, array('id' => $id));
        }
        else
        {
            $admin_data['user_password'] = encrypt_password(trim($data['password']));

            $this->db->insert('administrators', $admin_data);
        }

        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            if (!is_numeric($id))
            {
                $id = $this->db->insert_id();
            }
        }
        else
        {
            $error = TRUE;
        }

        //set the modules that the administrator could access
        if ($error === FALSE)
        {
            if (count($modules) > 0)
            {
                if (in_array('*', $modules))
                {
                    $modules = array('*');
                }

                foreach($modules as $module)
                {
                    $result = $this->db
                        ->select('administrators_id')
                        ->from('administrators_access')
                        ->where(array('administrators_id' => $id, 'module' => $module))
                        ->limit(1)
                        ->get();

                    if ($result->num_rows() < 1)
                    {
                        $this->db->insert('administrators_access', array('administrators_id' => $id, 'module' => $module));

                        //check transaction status
                        if ($this->db->trans_status() === FALSE)
                        {
                            $error = TRUE;
                            break;
                        }
                    }
                    
                    $result->free_result();
                }
            }
        }

        //delete the original modules which are not able to be accesses by the administrator
        if ($error === FALSE)
        {
            $this->db->where('administrators_id', $id);

            if (count($modules) > 0)
            {
                $this->db->where_not_in('module', $modules);
            }

            $this->db->delete('administrators_access');

            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }

        if ($error === FALSE)
        {
            //commit
            $this->db->trans_commit();

            return 1;
        }
        else
        {
            //rollback
            $this->db->trans_rollback();

            return -1;
        }
    }
    
    // ------------------------------------------------------------------------
  
    /**
     * Delete the administrator
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        //start transaction
        $this->db->trans_begin();

        $this->db->delete('administrators_access', array('administrators_id' => $id));

        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('administrators', array('id' => $id));
        }

        //check transaction status
        if ($this->db->trans_status() === TRUE)
        {
            //commit
            $this->db->trans_commit();

            return TRUE;
        }

        //rollback
        $this->db->trans_rollback();

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the data of the administrator with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('id, user_name, email_address')
            ->from('administrators')
            ->where('id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the data of the administrator with the email address
     *
     * @access public
     * @param $email
     * @return mixed
     */
    public function get_admin($email)
    {
        $result = $this->db
            ->select('id, user_name, email_address')
            ->from('administrators')
            ->where('email_address', $email)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Update the password of the administrator
     *
     * @access public
     * @param $email
     * @param $password
     * @return boolean
     */
    public function update_password($email, $password)
    {
        $this->db->update('administrators', array('user_password' => $password), array('email_address' => $email));

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the modules that the administrator could access
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_modules($id)
    {
        $result = $this->db
            ->select('module')
            ->from('administrators_access')
            ->where('administrators_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Check the email address
     *
     * @access public
     * @param $email
     * @return boolean
     */
    public function check_email($email)
    {
        $result = $this->db->select('id')->from('administrators')->where('email_address', $email)->get();

        if ($result->num_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get the total number of administrators
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('administrators');
    }
}

/* End of file administrators_model.php */
/* Location: ./system/models/administrators_model.php */