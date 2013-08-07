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
 * Message Stack
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	library-message_stack
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Message_Stack
{
    /**
     * ci instance
     *
     * @access private
     * @var string
     */
    private $ci = null;

    /**
     * Message stack content
     *
     * @access protected
     * @var array
     */
    protected $messages = array();

    /**
     * Message Stack Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();
        
        // Load message from session
        $this->load_from_session();
    }

    /**
     * Add content to message stack
     *
     * @param $field
     * @param $message
     * @param $type
     */
    function add($field, $message, $type = 'error')
    {
        $this->messages[] = array('field' => $field, 'type' => $type, 'message' => $message);
    }

    /**
     * Add content to message stack session
     *
     * @param $field
     * @param $message
     * @param $type
     */
    function add_session($field, $message, $type = 'error')
    {
        if ($this->ci->session->userdata('message_stack') !== FALSE)
        {
            $messages = $this->ci->session->userdata('message_stack');
        }
        else
        {
            $messages = array();
        }

        $messages[] = array('field' => $field, 'text' => $message, 'type' => $type);

        $this->ci->session->set_userdata('message_stack', $messages);

        $this->add($field, $message, $type);
    }

    /**
     * Reset message stack
     */
    function reset()
    {
        $this->messages = array();
    }

    /**
     * Get messages
     *
     * @param $field
     */
    function get_messages($field) {
        $messages = array();

        for ($i = 0, $n = sizeof($this->messages); $i < $n; $i++)
        {
            if ($this->messages[$i]['field'] == $field)
            {
                $messages[] = $this->messages[$i]['message'];
            }
        }

        return $messages;
    }

    /**
     * Output message stack
     *
     * @param $field
     * @return string
     */
    function output($field)
    {
        if (sizeof($this->messages) > 0)
        {
            $messages = '<ul>';

            for ($i = 0, $n = sizeof($this->messages); $i < $n; $i++)
            {
                if ($this->messages[$i]['field'] == $field)
                {
                    switch ($this->messages[$i]['type'])
                    {
                        case 'error':
                            $bullet_image = image_url('icons/error.gif');
                            break;
                        case 'warning':
                            $bullet_image = image_url('icons/warning.gif');
                            break;
                        case 'success':
                            $bullet_image = image_url('icons/success.gif');
                            break;
                        default:
                            $bullet_image = image_url('icons/bullet_default.gif');
                    }

                    $messages .= '<li style="list-style-image: url(\'' . $bullet_image . '\')">' . output_string($this->messages[$i]['message']) . '</li>';
                }
            }
            $messages .= '</ul>';

            return '<div class="alert alert-info">' . $messages . '</div>';
        }

        return '';
    }

    /**
     * Output message stack in plain way
     *
     * @param $field
     * @return string
     */
    function output_plain($field)
    {
        $message = FALSE;

        for ($i = 0, $n = sizeof($this->messages); $i < $n; $i++)
        {
            if ($this->messages[$i]['field'] == $field)
            {
                $message = output_string($this->messages[$i]['message']);
                break;
            }
        }

        return $message;
    }

    /**
     * Get the size of content for specified field
     *
     * @param $field
     * @return int
     */
    function size($field)
    {
        $field_size = 0;

        for ($i=0, $n=sizeof($this->messages); $i<$n; $i++)
        {
            if ($this->messages[$i]['field'] == $field)
            {
                $field_size++;
            }
        }

        return $field_size;
    }

    /**
     * Load messages from session
     */
    function load_from_session()
    {
        if ($this->ci->session->userdata('message_stack') !== FALSE)
        {
            $messages = $this->ci->session->userdata('message_stack');

            for ($i = 0, $n = sizeof($messages); $i < $n; $i++)
            {
                $this->add($messages[$i]['field'], $messages[$i]['text'], $messages[$i]['type']);
            }

            $this->ci->session->unset_userdata('message_stack');
        }
    }
}

/* End of file message_stack.php */
/* Location: ./system/tomatocart/libraries/message_stack.php */