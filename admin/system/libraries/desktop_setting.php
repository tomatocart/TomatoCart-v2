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
 * Desktop Setting
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Desktop_Setting
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    private $ci = NULL;

    /**
     * settings
     *
     * @access protected
     * @var array
     */
    private $settings = NULL;

    /**
     * user_name
     *
     * @access protected
     * @var string
     */
    private $user_name = NULL;

    /**
     * access
     *
     * @access protected
     * @var string
     */
    private $access = NULL;

    /**
     * modules
     *
     * @access protected
     * @var string
     */
    private $modules = array();

    /**
     * Default Constructor
     *
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci = & get_instance();

        //load desktop settings model
        $this->ci->load->model('desktop_settings_model');

        //load access
        $this->ci->load->library('access');

        $admin_data = $this->ci->session->userdata('admin_data');

        $this->user_name = $admin_data['name'];

        $this->initialize();
    }

    /**
     * Initialize the desktop settings
     *
     * @access private
     */
    private function initialize()
    {
        //get admin settings
        $settings = $this->ci->desktop_settings_model->get_settings($this->user_name);

        //if the admin does not has settingss
        if ( !is_array($settings) || empty($settings) || !isset($settings['desktop']) )
        {
            $this->settings = $this->get_default_settings();

            $this->save($this->user_name, $this->settings);
        }
        else
        {
            $this->settings = $settings['desktop'];
        }

        //get access modules
        $access = $this->ci->access->get_levels();
        ksort($access);

        $this->access = $access;

        $modules = array();
        foreach($access as $group => $links)
        {
            $modules[] = $group;

            foreach ($links as $link)
            {
                $module = $link['module'];

                if ( is_array($link['subgroups']) && !empty($link['subgroups']) )
                {
                    $modules[] = $module;

                    foreach ($link['subgroups'] as $subgroup)
                    {
                        $modules[] = $module;
                    }
                }
                else
                {
                    $modules[] = $module;
                }
            }
        }

        $this->modules = $modules;
    }

    /**
     * Save desktop settings
     *
     * @access public
     * @param $data
     */
    public function save_desktop($data)
    {
        $this->settings['autorun'] = $data['autorun'];
        $this->settings['quickstart'] = $data['quickstart'];
        $this->settings['contextmenu'] = $data['contextmenu'];
        $this->settings['shortcut'] = $data['shortcut'];
        $this->settings['theme'] = $data['theme'];
        $this->settings['wallpaper'] = $data['wallpaper'];
        $this->settings['transparency'] = $data['transparency'];
        $this->settings['backgroundcolor'] = $data['backgroundcolor'];
        $this->settings['fontcolor'] = $data['fontcolor'];
        $this->settings['wallpaperposition'] = $data['wallpaperposition'];

        return $this->save($this->user_name, $this->settings);
    }

    /**
     * Get username
     *
     * @access public
     * @return string
     */
    public function get_username()
    {
        return $this->user_name;
    }

    /**
     * Construct the menu
     *
     * @access public
     * @return array
     */
    public function get_modules()
    {
        $modules = array();
        foreach ($this->access as $group => $links)
        {
            $modules[] = 'new Toc.desktop.' . ucfirst($group) . 'GroupWindow()';

            foreach ($links as $link)
            {
                $module = str_replace(' ', '', ucwords(str_replace('_', ' ', $link['module'])));

                if ( is_array($link['subgroups']) && !empty($link['subgroups']) ) {
                    $modules[] = 'new Toc.desktop.' . $module . 'SubGroupWindow()';

                    foreach ( $link['subgroups'] as $subgroup ) {
                        $params = isset($subgroup['params']) ? $subgroup['params'] : NULL;
                        $modules[] = 'new Toc.desktop.' . $module .
            'Window({id: \'' . $subgroup['identifier'] . '\', title: \'' . $subgroup['title'] . '\', iconCls: \'' . $subgroup['iconCls'] . '\', shortcutIconCls: \'' . $subgroup['shortcutIconCls'] . '\', params: ' . json_encode($params) . '})';
                    }
                }else {
                    $modules[] = 'new Toc.desktop.' . $module . 'Window()';
                }
            }
        }

        $modules[] = 'new Toc.desktop.LanguagesGroupWindow()';
        $languages = array();
        foreach ( $this->ci->lang->get_languages() as $l ) {
            $modules[] = 'new Toc.desktop.' . str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($l['code'])))) . 'Window()';
        }

        $menu = '[' . implode(',' , $modules) . ']';
         
        return $menu;
    }

    /**
     * Get launchers
     *
     * @access public
     * @return array
     */
    public function get_launchers()
    {
        $autorun     = (isset($this->settings['autorun']) && !empty($this->settings['autorun'])) ? $this->settings['autorun'] : '[]';
        $shortcut    = (isset($this->settings['shortcut']) && !empty($this->settings['shortcut'])) ? $this->settings['shortcut'] : '[]';
        $quickstart  = (isset($this->settings['quickstart']) && !empty($this->settings['quickstart'])) ? $this->settings['quickstart'] : '[]';
        $contextmenu = (isset($this->settings['contextmenu']) && !empty($this->settings['contextmenu'])) ? $this->settings['contextmenu'] : '[]';

        $launchers = array();
        $launchers['autorun'] = $autorun;
        $launchers['contextmenu'] = $contextmenu;
        $launchers['quickstart'] = $quickstart;
        $launchers['shortcut'] = $shortcut;

        return "{'autorun': " . $autorun . ",
              'contextmenu': " . $contextmenu . ", 
              'quickstart': " . $quickstart . ",
              'shortcut': " . $shortcut . "}";
    }

    /**
     * Get styles
     *
     * @access public
     * @return array
     */
    public function get_styles()
    {
        $backgroundcolor = (isset($this->settings['backgroundcolor']) && !empty($this->settings['backgroundcolor'])) ? $this->settings['backgroundcolor'] : '#3A6EA5';
        $fontcolor = (isset($this->settings['fontcolor']) && !empty($this->settings['fontcolor'])) ? $this->settings['fontcolor'] : 'FFFFFF';
        $transparency = (isset($this->settings['transparency']) && !empty($this->settings['transparency'])) ? $this->settings['transparency'] : '100';
        $wallpaperposition = (isset($this->settings['wallpaperposition']) && !empty($this->settings['wallpaperposition'])) ? $this->settings['wallpaperposition'] : 'tile';

        $styles = array();
        $styles['backgroundcolor'] = $backgroundcolor;
        $styles['fontcolor'] = $fontcolor;
        $styles['theme'] = '';
        $styles['transparency'] = $transparency;
        $styles['wallpaper'] = $this->get_wallpaper();
        $styles['wallpaperposition'] = $wallpaperposition;

        return json_encode($styles);
    }

    /**
     * Output modules
     *
     * @access public
     * @return string
     */
    public function output_modules()
    {
        $output = '';

        foreach ($this->access as $group => $links)
        {
            $group_class = '';
            $modules = array();

            foreach ( $links as $link ) {
                if ( is_array($link['subgroups']) && !empty($link['subgroups']) ) {
                    $modules[] = '\'' . $link['module'] . '-subgroup' . '\'';
                } else {
                    $modules[] = '\'' . $link['module'] . '-win' . '\'';
                }
            }

            $group_class = 'Toc.desktop.' . ucfirst($group) . 'GroupWindow = Ext.extend(Toc.desktop.Module, {' . "\n";
            $group_class .= 'appType : \'group\',' . "\n";
            $group_class .= 'id : \'' . $group . '-grp\',' . "\n";
            $group_class .= 'title : \'' . $this->ci->access->get_group_title($group) . '\',' . "\n";
            $group_class .= 'menu : new Ext.menu.Menu(),' . "\n";
            $group_class .= 'items : [' . implode(',' , $modules) . '],' . "\n";
            $group_class .= 'init : function(){' . "\n";
            $group_class .= 'this.launcher = {' . "\n";
            $group_class .= 'text: this.title,' . "\n";
            $group_class .= 'iconCls: \'icon-' . $group . '-grp\',' . "\n";
            $group_class .= 'menu: this.menu' . "\n";
            $group_class .= '}}});' . "\n" . "\n";

            $output .= $group_class;

            foreach ( $links as $link ) {
                if ( is_array($link['subgroups']) && !empty($link['subgroups']) ) {
                    $modules = array();

                    foreach ( $link['subgroups'] as $subgroup ) {
                        $modules[] = '\'' . $subgroup['identifier'] . '\'';
                    }

                    $group_class = '';
                    $module = str_replace(' ', '', ucwords(str_replace('_', ' ', $link['module'])));
                    $group_class = 'Toc.desktop.' . $module . 'SubGroupWindow = Ext.extend(Toc.desktop.Module, {' . "\n";
                    $group_class .= 'appType : \'subgroup\',' . "\n";
                    $group_class .= 'id : \'' . $link['module'] . '-subgroup\',' . "\n";
                    $group_class .= 'title : \'' . htmlentities($link['title'], ENT_QUOTES, 'UTF-8') . '\',' . "\n";
                    $group_class .= 'menu : new Ext.menu.Menu(),' . "\n";
                    $group_class .= 'items : [' . implode(',' , $modules) . '],' . "\n";
                    $group_class .= 'init : function(){' . "\n";
                    $group_class .= 'this.launcher = {' . "\n";

                    $group_class .= 'text: this.title,' . "\n";
                    $group_class .= 'iconCls: \'icon-' . $link['module'] . '-subgroup\',' . "\n";
                    $group_class .= 'menu: this.menu' . "\n";
                    $group_class .= '}}});' . "\n" . "\n";

                    $output .= $group_class;

                    $group_class = '';
                    $module = str_replace(' ', '', ucwords(str_replace('_', ' ', $link['module'])));
                    $group_class = 'Toc.desktop.' . $module . 'Window = Ext.extend(Toc.desktop.Module, {' . "\n";
                    $group_class .= 'appType : \'win\',' . "\n";
                    $group_class .= 'id : \'' . $link['module'] . '-win\',' . "\n";
                    $group_class .= 'title: \'' . htmlentities($link['title'], ENT_QUOTES, 'UTF-8') . '\',' . "\n";
                    $group_class .= 'init : function(){' . "\n";
                    $group_class .= 'this.launcher = {' . "\n";
                    $group_class .= 'text: this.title,' . "\n";
                    $group_class .= 'iconCls: this.iconCls,' . "\n";
                    $group_class .= 'shortcutIconCls: this.shortcutIconCls,' . "\n";
                    $group_class .= 'scope: this' . "\n";
                    $group_class .= '}}});' . "\n" . "\n";

                    $output .= $group_class;

                } else {
                    $group_class = '';
                    $module = str_replace(' ', '', ucwords(str_replace('_', ' ', $link['module'])));
                    $group_class = 'Toc.desktop.' . $module . 'Window = Ext.extend(Toc.desktop.Module, {' . "\n";
                    $group_class .= 'appType : \'win\',' . "\n";
                    $group_class .= 'id : \'' . $link['module'] . '-win\',' . "\n";
                    $group_class .= 'title: \'' . htmlentities($link['title'], ENT_QUOTES, 'UTF-8') . '\',' . "\n";
                    $group_class .= 'init : function(){' . "\n";
                    $group_class .= 'this.launcher = {' . "\n";


                    $group_class .= 'text: this.title,' . "\n";
                    $group_class .= 'iconCls: \'icon-' . $link['module'] . '-win\',' . "\n";
                    $group_class .= 'shortcutIconCls: \'icon-' . $link['module'] . '-shortcut\',' . "\n";

                    $group_class .= 'scope: this' . "\n";
                    $group_class .= '}}});' . "\n" . "\n";

                    $output .= $group_class;
                }
            }
        }

        $output .= $this->get_lang_modules();

        return $output;
    }


    function get_lang_modules()
    {
        $languages = array();

        foreach ( $this->ci->lang->get_languages() as $l )
        {
            $languages[] = '\'lang-' . strtolower($l['code']) . '-win' . '\'';
        }

        $output = 'Toc.desktop.LanguagesGroupWindow = Ext.extend(Toc.desktop.Module, {' . "\n";
        $output .= 'appType : \'group\',' . "\n";
        $output .= 'id : \'languages-grp\',' . "\n";
        $output .= 'title : \'' . $this->ci->access->get_group_title('languages') . '\',' . "\n";
        $output .= 'menu : new Ext.menu.Menu(),' . "\n";
        $output .= 'items : [' . implode(',', $languages) . '],' . "\n";
        $output .= 'init : function(){' . "\n";
        $output .= 'this.launcher = {' . "\n";
        $output .= 'text: \'' . lang('header_title_languages') . '\',' . "\n";
        $output .= 'iconCls: \'icon-languages-grp\',' . "\n";
        $output .= 'menu: this.menu';
        $output .= '}';
        $output .= '}';
        $output .= '});' . "\n" . "\n";

        foreach ( $this->ci->lang->get_languages() as $l ) {

            $output .= 'Toc.desktop.' . str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($l['code'])))) . 'Window = Ext.extend(Toc.desktop.Module, {' . "\n";
            $output .= 'appType : \'win\',' . "\n";
            $output .= 'id : \'lang-' . strtolower($l['code']) . '-win\',' . "\n";
            $output .= 'title: \'' . $l['name'] . '\',' . "\n";
            $output .= 'init : function(){' . "\n";
            $output .= 'this.launcher = {' . "\n";
            $output .= 'text: \'' . $l['name'] . '\',' . "\n";
            $output .= 'iconCls: \'icon-' . $l['country_iso'] . '-win\',' . "\n";
            $output .= 'shortcutIconCls: \'icon-' . $l['code'] . '-shortcut\',' . "\n";
            $output .= 'handler: function(){window.location = "' . site_url('index').  '?admin_language=' . $l['code'] . '";},' . "\n";
            $output .= 'scope: this';
            $output .= '}' . "\n";
            $output .= '}' . "\n";
            $output .= '});' . "\n";
        }

        return $output;
    }

    /**
     * List modules
     *
     * @access public
     * @param $settings
     */
    public function list_modules($settings)
    {
        $autorun = (explode(",", (substr($settings['autorun'], 1, strlen($settings['autorun'])-2))));
        $contextmenu = explode(",", (substr($settings['contextmenu'], 1, strlen($settings['contextmenu'])-2)));
        $quickstart = (explode(",", (substr($settings['quickstart'], 1, strlen($settings['quickstart'])-2))));
        $shortcut = (explode(",", (substr($settings['shortcut'], 1, strlen($settings['shortcut'])-2))));

        $modules = array();
        foreach ( $this->access as $group => $links )
        {
            $module = htmlentities($this->ci->access->get_group_title($group), ENT_QUOTES, 'UTF-8');
            foreach ( $links as $link )
            {
                $secmodule = ucwords(str_replace('_', ' ', $link['module']));

                if ( is_array($link['subgroups']) && !empty($link['subgroups']) )
                {
                    foreach ( $link['subgroups'] as $subgroup )
                    {

                        $Aautorun = $this->loop_launcher($autorun, $subgroup['identifier']);
                        $Acontextmenu = $this->loop_launcher($contextmenu, $subgroup['identifier']);
                        $Aquickstart = $this->loop_launcher($quickstart, $subgroup['identifier']);
                        $Ashortcut = $this->loop_launcher($shortcut, $subgroup['identifier']);

                        $modules[] = array('parent' => $module,
                               'text'=>htmlentities($subgroup['title'], ENT_QUOTES, 'UTF-8'),
                               'id'=>$subgroup['identifier'],
                               'autorun'=>$Aautorun,
                               'contextmenu'=>$Acontextmenu,
                               'quickstart'=>$Aquickstart,
                               'shortcut'=>$Ashortcut);
                    }
                }
                else
                {
                    $link['module'] = $link['module'].'-win';
                    $Aautorun = $this->loop_launcher($autorun, $link['module']);
                    $Acontextmenu = $this->loop_launcher($contextmenu, $link['module']);
                    $Aquickstart = $this->loop_launcher($quickstart, $link['module']);
                    $Ashortcut = $this->loop_launcher($shortcut, $link['module']);
                    $modules[] = array('parent' => $module,
                             'text'=>htmlentities($secmodule, ENT_QUOTES, 'UTF-8'),
                             'id'=> $link['module'],
                             'autorun'=>$Aautorun,
                             'contextmenu'=>$Acontextmenu,
                             'quickstart'=>$Aquickstart,
                             'shortcut'=>$Ashortcut);
                }
            }
        }

        return $modules;
    }

    /**
     * Loop launchers
     *
     * @access public
     * @param $launcher
     * @param $module
     * @return string
     */
    private function loop_launcher($launcher, $module)
    {
        $result = FALSE;
        foreach ($launcher as $value) {
            $value = str_replace('"', '', $value );

            if ( strcmp($module, $value) ==0 ) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get settings
     *
     * @access public
     * @return array
     */
    public function get_settings()
    {
        return $this->settings;
    }

    /**
     * Get wallpaper
     *
     * @access public
     * @return array
     */
    private function get_wallpaper()
    {
        $code = (isset($this->settings['wallpaper']) && !empty($this->settings['wallpaper'])) ? $this->settings['wallpaper'] : 'blank';

        $wallpapers = $this->get_wallpapers();
        $path = '';
        foreach($wallpapers as $tmp) {
            if($code == $tmp['code'])
            {
                $path = $tmp['path'];
                break;
            }
        }

        $wallpaper = array();
        $wallpaper['code'] = $code;
        $wallpaper['path'] = $path;

        return $wallpaper;
    }

    /**
     * Get wallpapers
     *
     * @access public
     * @return array
     */
    public function get_wallpapers()
    {
        $result = simplexml_load_file('templates/base/web/javascript/desktop/wallpapers/wallpapers.xml');

        $wallpapers = array();
        foreach ($result->Wallpaper as $wallpaper) {
            $wallpapers[] = array(
        'code' => strval($wallpaper->Code),
        'name' => strval($wallpaper->Name),
        'thumbnail' => $this->ci->config->base_url() . strval($wallpaper->Thumbnail),
        'path' => $this->ci->config->base_url() .strval($wallpaper->File)
            );
        }

        return $wallpapers;
    }

    /**
     * Get default settings
     *
     * @access public
     * @return array
     */
    private function get_default_settings() {
        $settings = array();

        $settings['theme'] = 'vistablue';
        $settings['transparency'] = '100';
        $settings['backgroundcolor'] = '3A6EA5';
        $settings['fontcolor'] = 'FFFFFF';
        $settings['wallpaper'] = 'blank';
        $settings['wallpaperposition'] = 'tile';

        $settings['autorun'] = '["dashboard-win"]';
        $settings['contextmenu'] = '[]';
        $settings['quickstart'] = '["articles_categories-win","articles-win","faqs-win","slide_images-win","products-win","customers-win","orders-win", "invoices-win", "coupons-win","gift_certificates-win","dashboard-win"]';
        $settings['shortcut'] = '["articles_categories-win","articles-win","faqs-win","slide_images-win","products-win","customers-win","orders-win", "invoices-win", "coupons-win","gift_certificates-win","dashboard-win"]';
        $settings['wizard_complete'] = FALSE;

        $settings['dashboards'] = 'overview:0,new_orders:1,new_customers:2,new_reviews:0,orders_statistics:1,last_visits:2';

        $settings['livefeed'] = 0;

        return $settings;
    }

    /**
     * Save user desktop settings
     *
     * @param $username
     * @param $data
     * @return boolean
     */
    private function save($username, $data) {
        if ($this->ci->desktop_settings_model->save_settings($username, $data) == TRUE)
        {
            return TRUE;
        }

        return FALSE;
    }
}

?>