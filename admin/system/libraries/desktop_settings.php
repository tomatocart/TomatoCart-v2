<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TOC_Desktop_Settings
 *
 * @author TomatoCart Dev Team
 */

class TOC_Desktop_Settings {
    private $_settings = null;
    private $_user_name = null;
    private $_access = null;
    private $_modules = array();

    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->_ci = & get_instance();

        $admin_data = $this->_ci->session->userdata('admin_data');

        $this->_user_name = $admin_data['name'];

        $this->initialize();
    }

    private function initialize() {
        //initialize settings
        $this->_ci->load->model('desktop_settings_model');

        $settings = $this->_ci->desktop_settings_model->get_settings($this->_user_name);

        if ( !is_array($settings) || empty($settings) || !isset($settings['desktop']) )
        {
            $this->_settings = $this->get_default_settings();

            $this->save($this->_user_name, $this->_settings);
        }
        else
        {
            $this->_settings = $settings['desktop'];
        }

        //initializing modules
        $this->_ci->load->library('access');

        $access = $this->_ci->access->get_levels();
        ksort($access);

        $this->_access = $access;

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

        $this->_modules = $modules;
    }

    public function save_desktop($data)
    {
        $this->_settings['autorun'] = $data['autorun'];
        $this->_settings['quickstart'] = $data['quickstart'];
        $this->_settings['contextmenu'] = $data['contextmenu'];
        $this->_settings['shortcut'] = $data['shortcut'];
        $this->_settings['theme'] = $data['theme'];
        $this->_settings['wallpaper'] = $data['wallpaper'];
        $this->_settings['transparency'] = $data['transparency'];
        $this->_settings['backgroundcolor'] = $data['backgroundcolor'];
        $this->_settings['fontcolor'] = $data['fontcolor'];
        $this->_settings['wallpaperposition'] = $data['wallpaperposition'];

        return $this->save($this->_user_name, $this->_settings);
    }

    public function get_username()
    {
        return $this->_user_name;
    }

    public function get_modules()
    {
        $modules = array();
        foreach ($this->_access as $group => $links )
        {
            $modules[] = 'new Toc.desktop.' . ucfirst($group) . 'GroupWindow()';

            foreach ($links as $link)
            {
                $module = str_replace(' ', '', ucwords(str_replace('_', ' ', $link['module'])));

                if ( is_array($link['subgroups']) && !empty($link['subgroups']) ) {
                    $modules[] = 'new Toc.desktop.' . $module . 'SubGroupWindow()';

                    foreach ( $link['subgroups'] as $subgroup ) {
                        $params = isset($subgroup['params']) ? $subgroup['params'] : null;
                        $modules[] = 'new Toc.desktop.' . $module .
            'Window({id: \'' . $subgroup['identifier'] . '\', title: \'' . $subgroup['title'] . '\', iconCls: \'' . $subgroup['iconCls'] . '\', shortcutIconCls: \'' . $subgroup['shortcutIconCls'] . '\', params: ' . json_encode($params) . '})';
                    }
                }else {
                    $modules[] = 'new Toc.desktop.' . $module . 'Window()';
                }
            }
        }

        $menu = '[' . implode(',' , $modules) . ']';
         
        return $menu;
    }

    public function get_launchers()
    {
        $autorun     = (isset($this->_settings['autorun']) && !empty($this->_settings['autorun'])) ? $this->_settings['autorun'] : '[]';
        $shortcut    = (isset($this->_settings['shortcut']) && !empty($this->_settings['shortcut'])) ? $this->_settings['shortcut'] : '[]';
        $quickstart  = (isset($this->_settings['quickstart']) && !empty($this->_settings['quickstart'])) ? $this->_settings['quickstart'] : '[]';
        $contextmenu = (isset($this->_settings['contextmenu']) && !empty($this->_settings['contextmenu'])) ? $this->_settings['contextmenu'] : '[]';

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

    public function get_styles()
    {
        $backgroundcolor = (isset($this->_settings['backgroundcolor']) && !empty($this->_settings['backgroundcolor'])) ? $this->_settings['backgroundcolor'] : '#3A6EA5';
        $fontcolor = (isset($this->_settings['fontcolor']) && !empty($this->_settings['fontcolor'])) ? $this->_settings['fontcolor'] : 'FFFFFF';
        $transparency = (isset($this->_settings['transparency']) && !empty($this->_settings['transparency'])) ? $this->_settings['transparency'] : '100';
        $wallpaperposition = (isset($this->_settings['wallpaperposition']) && !empty($this->_settings['wallpaperposition'])) ? $this->_settings['wallpaperposition'] : 'tile';

        $styles = array();
        $styles['backgroundcolor'] = $backgroundcolor;
        $styles['fontcolor'] = $fontcolor;
        $styles['theme'] = '';
        $styles['transparency'] = $transparency;
        $styles['wallpaper'] = $this->get_wallpaper();
        $styles['wallpaperposition'] = $wallpaperposition;

        return json_encode($styles);
    }

    public function output_modules()
    {
        $output = '';

        foreach ($this->_access as $group => $links)
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
            $group_class .= 'title : \'' . $this->_ci->access->get_group_title($group) . '\',' . "\n";
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
        }exit;

        $output .= $this->get_lang_modules();

        return $output;
    }

    function get_lang_modules()
    {
        $languages = array();

        foreach ( $this->ci->get_languages() as $l )
        {
            $languages[] = '\'lang-' . strtolower($l['code']) . '-win' . '\'';
        }

        $output = 'Toc.desktop..LanguagesGroupWindow = Ext.extend(Ext.app.Module, {';
        $output .= 'appType : \'group\',';

        $output .= 'id : \'languages-grp\',';
        $output .= 'menu : new Ext.menu.Menu(),';
        $output .= 'items : [' . implode(',', $languages) . '],';
        $output .= 'init : function(){';
        $output .= 'this.launcher = {';
        $output .= 'text: \'' . lang('header_title_languages') . '\',';
        $output .= 'iconCls: \'icon-languages-grp\',';
        $output .= 'menu: this.menu';
        $output .= '}';
        $output .= '}';
        $output .= '});';

        foreach ( $this->ci->get_languages() as $l ) {

            $output .= 'Toc.desktop..' . str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($l['code'])))) . 'Window = Ext.extend(Ext.app.Module, {';
            $output .= 'appType : \'grid\',';
            $output .= 'id : \'lang-' . strtolower($l['code']) . '-win\',';
            $output .= 'init : function(){';
            $output .= 'this.launcher = {';
            $output .= 'text: \'' . $l['name'] . '\',';
            $output .= 'iconCls: \'icon-' . $l['country_iso'] . '-win\',';
            $output .= 'shortcutIconCls: \'icon-' . $l['code'] . '-shortcut\',';
            $output .= 'handler: function(){window.location = "' . site_url('index').  '?admin_language=' . $l['code'] . '";},';
            $output .= 'scope: this';
            $output .= '}';
            $output .= '}';
            $output .= '});';
        }
        
        echo $output;exit;

        return $output;
    }

    public function list_modules($settings)
    {
        $autorun = (explode(",", (substr($settings['autorun'], 1, strlen($settings['autorun'])-2))));
        $contextmenu = explode(",", (substr($settings['contextmenu'], 1, strlen($settings['contextmenu'])-2)));
        $quickstart = (explode(",", (substr($settings['quickstart'], 1, strlen($settings['quickstart'])-2))));
        $shortcut = (explode(",", (substr($settings['shortcut'], 1, strlen($settings['shortcut'])-2))));

        $modules = array();
        foreach ( $this->_access as $group => $links )
        {
            $module = htmlentities($this->_ci->access->get_group_title($group), ENT_QUOTES, 'UTF-8');
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

    private function loop_launcher($launcher, $module)
    {
        $result = false;
        foreach ($launcher as $value) {
            $value = str_replace('"', '', $value );

            if ( strcmp($module, $value) ==0 ) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    public function get_settings()
    {
        return $this->_settings;
    }

    private function get_wallpaper()
    {
        $code = (isset($this->_settings['wallpaper']) && !empty($this->_settings['wallpaper'])) ? $this->_settings['wallpaper'] : 'blank';

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

    public function get_wallpapers()
    {
        $result = simplexml_load_file(realpath(APPPATH . 'views/default/desktop/wallpapers/wallpapers.xml'));

        $wallpapers = array();
        foreach ($result->Wallpaper as $wallpaper) {
            $wallpapers[] = array(
        'code' => strval($wallpaper->Code),
        'name' => strval($wallpaper->Name),
        'thumbnail' => $this->_ci->config->base_url() . APPPATH . strval($wallpaper->Thumbnail),
        'path' => $this->_ci->config->base_url() . APPPATH . strval($wallpaper->File)
            );
        }

        return $wallpapers;
    }

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

    private function save($username, $data) {
        if ($this->_ci->desktop_settings_model->save_settings($username, $data) == TRUE)
        {
            return TRUE;
        }

        return FALSE;
    }
}

?>