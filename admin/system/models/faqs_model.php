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
 * Faqs Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com
 */

class Faqs_Model extends CI_Model
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
    
    // --------------------------------------------------------------------

    /**
     * Get the faqs
     * 
     * @access public
     * @param $start
     * @param $limit
     * @param $search
     * @return mixed
     */
    public function get_faqs($start = NULL, $limit = NULL, $search = NULL)
    {
        $this->db
            ->select('f.faqs_id, f.faqs_status, f.faqs_order, fd.faqs_question')
            ->from('faqs f')
            ->join('faqs_description fd', 'f.faqs_id = fd.faqs_id')
            ->where('fd.language_id', lang_id());
        
        if ($search !== NULL)
        {
            $this->db->like('fd.faqs_question', $search);
        }
        
        $this->db->order_by('f.faqs_id desc');
        
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
    
    // --------------------------------------------------------------------

    /**
     * Save the faq
     * 
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data = array())
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        //process faqs
        if (is_numeric($id))
        {
            $this->db->update('faqs',array('faqs_status' => $data['faqs_status'], 
                                           'faqs_order' => $data['faqs_order'], 
                                           'faqs_last_modified' => date('Y-m-d H:i:s')), 
                                     array('faqs_id' => $id));
        }
        else
        {
            $this->db->insert('faqs', array('faqs_status' => $data['faqs_status'], 'faqs_order' => $data['faqs_order'], 'faqs_date_added' => date('Y-m-d H:i:s')));
        }
        
        if ($this->db->trans_status() === FALSE)
        {
            $error = TRUE;
        }
        else
        {
            $faqs_id = is_numeric($id) ? $id : $this->db->insert_id();
        }
        
        //process faqs description
        if ($error === FALSE)
        {
            //languages
            foreach(lang_get_all() as $l)
            {
                $decription_data = array('faqs_question' => $data['faqs_question'][$l['id']], 
                                         'faqs_url' => $data['faqs_url'][$l['id']], 
                                         'faqs_answer' => $data['faqs_answer'][$l['id']]);
                if (is_numeric($id))
                {
                     $this->db->update('faqs_description', $decription_data, array('faqs_id' => $faqs_id, 'language_id' => $l['id']));
                }
                else
                {
                    $decription_data['faqs_id'] = $faqs_id;
                    $decription_data['language_id'] = $l['id'];
                    
                    $this->db->insert('faqs_description', $decription_data);
                }
                
                //check transaction status
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
                }
               
            }
        }
        
        if ($error === FALSE)
        {
            //commit transaction
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get the data of the faqs
     * 
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
            ->select('f.*, fd.*')
            ->from('faqs f')
            ->join('faqs_description fd', 'f.faqs_id =fd.faqs_id')
            ->where(array('f.faqs_id' => $id, 'fd.language_id' => lang_id()))
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get the descriptions of the faq
     * 
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_description($id)
    {
        $result = $this->db
            ->select('faqs_question, faqs_url, faqs_answer, language_id')
            ->from('faqs_description')
            ->where('faqs_id', $id)
            ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Delete the faq
     * 
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $error = FALSE;
        
        //start transaction
        $this->db->trans_begin();
        
        $this->db->delete('faqs_description', array('faqs_id' => $id));
        
        //check transaction status
        if ($this->db->trans_status() === FALSE)
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
        {
            $this->db->delete('faqs', array('faqs_id' => $id));
            
            //check transaction status
            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }
        
        if ($error === FALSE)
        {
            //commit transaction
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        //rollback
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Set the status of faq
     * 
     * @access public
     * @param $id
     * @param $flag
     * @return boolean
     */
    public function set_status($id, $flag)
    {
        $this->db->update('faqs', array('faqs_status' => $flag, 'faqs_last_modified' => date('Y-m-d H:i:s')), array('faqs_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get the total number of faqs
     * 
     * @access public
     * @param $search
     * @return int
     */
    public function get_total($search = NULL)
    {
        $this->db
            ->select('f.faqs_id, f.faqs_status, f.faqs_order, fd.faqs_question')
            ->from('faqs f')
            ->join('faqs_description fd', 'f.faqs_id = fd.faqs_id')
            ->where('fd.language_id', lang_id());
        
        if ($search !== NULL)
        {
            $this->db->like('fd.faqs_question', $search);
        }
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
}

/* End of file faqs_model.php */
/* Location: ./system/models/faqs_model.php */