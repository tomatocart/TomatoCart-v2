<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Template Class
 *
 * This template library is derived from Phil Sturgeon's (Pyrocms) template and themeing library.
 *
 * Build your CodeIgniter pages much easier with modules, breadcrumbs, layouts and templates
 *
 * @package     CodeIgniter
 * @subpackage    Libraries
 * @category    Libraries
 * @author      Philip Sturgeon
 * @license     http://philsturgeon.co.uk/code/dbad-license
 * @link      http://philsturgeon.co.uk/code/codeigniter-template
 */
class Template
{
    /**
     * Holds the title of the page module
     *
     * @var string
     * @access private
     */
    private $_module = 'index';

    /**
     * Holds the controller
     *
     * @var string
     * @access private
     */
    private $_controller = 'index';

    /**
     * Holds the controller method
     *
     * @var string
     * @access private
     */
    private $_method = 'index';

    /**
     * template name
     *
     * @var string
     * @access private
     */
    private $_template = 'default';

    /**
     * template id
     *
     * @var int
     * @access private
     */
    private $_template_id = 1;

    /**
     * template path
     *
     * @var string
     * @access private
     */
    private $_template_path = 'templates/default/';

    /**
     * layout file name
     * By default, dont wrap the view with anything
     *
     * @var array
     * @access private
     */
    private $_layout = FALSE;

    /**
     * layout file folder
     * Layouts and modules will exist in views/layouts
     *
     * @var array
     * @access private
     */
    private $_layout_subdir = '';

    /**
     * page title
     *
     * @var array
     * @access private
     */
    private $_title = '';

    /**
     * page meta title (Keywords, Description, Generator)
     *
     * @var array
     * @access private
     */
    private $_meta_tags = array('generator' => array('TomatoCart -- Open Source Shopping Cart Solution'));

    /**
     * Holds javascript filenames to be included in the page
     *
     * @var array
     * @access private
     */
    var $_javascript_files = array();

    /**
     * Holds blocks of javascript syntax to embedd into the page, each block must contain its relevant <script> and </script> tags
     *
     * @var array
     * @access private
     */
    var $_javascript_blocks = array();

    /**
     * Holds style sheet files to be included in the page
     *
     * @var array
     * @access private
     */
    var $_stylesheet_files = array();

    /**
     * Holds style declarations to embedd into the page head
     *
     * @var array
     * @access private
     */
    var $_stylesheet_blocks = array();

    /**
     * Defines if the requested page has a header
     *
     * @var boolean
     * @access private
     */

    var $_has_header = true;

    /**
     * Defines if the requested page has a footer
     *
     * @var boolean
     * @access private
     */
    var $_has_footer = true;

    /**
     * Defines if the requested page has modules
     *
     * @var boolean
     * @access private
     */
    var $_has_modules = true;

    /**
     * Holds the content of the module groups
     *
     * @var array
     * @access private
     */
    private $_module_groups = array();

    /**
     * Holds the content of breadcrumbs
     *
     * @var array
     * @access private
     */
    private $_breadcrumbs = array();

    /*page title seperator*/
    private $_title_separator = ' | ';

    /*page title seperator*/
    private $_parser_enabled = FALSE;
    private $_parser_body_enabled = FALSE;

    /**
     * Holds the template physical location
     *
     * @var string
     * @access private
     */
    private $_templates_location = 'templates/';

    /**/
    private $_is_mobile = FALSE;

    /**/
    private $_is_pad = FALSE;

    // Minutes that cache will be alive for
    private $cache_lifetime = 0;

    private $_ci;

    private $_data = array();

    /**
     * Constructor - Sets Preferences
     *
     * The constructor can be passed an array of config values
     * @param $config an array of config values
     */
    function __construct($config = array())
    {
        $this->_ci =& get_instance();

        $this->initialize($config);

        log_message('debug', 'Template class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initialize preferences
     *
     * @access  public
     * @param array
     * @return  void
     */
    function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if ($key == 'template' AND !empty($val))
            {
                $this->set_template($val);
                continue;
            }

            $this->{'_' . $key} = $val;
        }

        // template was set
        if ($this->_template)
        {
            $this->set_template($this->_template);
        }

        // If the parse is going to be used, best make sure it's loaded
        if ($this->_parser_enabled === TRUE)
        {
            class_exists('CI_Parser') OR $this->_ci->load->library('parser');
        }

        // Modular Separation / Modular Extensions has been detected
        if (method_exists( $this->_ci->router, 'fetch_module' ))
        {
            $this->_module  = $this->_ci->router->fetch_module();
        }

        // What controllers or methods are in use
        $this->_controller  = $this->_ci->router->fetch_class();
        $this->_method    = $this->_ci->router->fetch_method();

        // Load user agent library if not loaded
        class_exists('CI_User_agent') OR $this->_ci->load->library('user_agent');

        // We'll want to know this later
        $this->_is_mobile = $this->_ci->agent->is_mobile();
        $this->_is_pad = $this->_ci->agent->is_pad();
    }

    // --------------------------------------------------------------------

    /**
     * Magic Get function to get data
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
    }

    // --------------------------------------------------------------------

    /**
     * Magic Set function to set data
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    // --------------------------------------------------------------------

    /**
     * Set data using a chainable metod. Provide two strings or an array of data.
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public function set($name, $value = NULL)
    {
        // Lots of things! Set them all
        if (is_array($name) OR is_object($name))
        {
            foreach ($name as $item => $value)
            {
                $this->_data[$item] = $value;
            }
        }

        // Just one thing, set that
        else
        {
            $this->_data[$name] = $value;
        }

        return $this;
    }

    public function set_title($title)
    {
        $this->_title = $title;
    }

    // --------------------------------------------------------------------

    /**
     * Build the entire HTML output combining modules, layouts and views.
     *
     * @access  public
     * @param string

     * @return  void
     */
    public function build($view, $data = array(), $return = FALSE)
    {
        //add current page to the navigation history
        $this->_ci->navigation_history->add_current_page();
        
        // Set whatever values are given. These will be available to all view files
        is_array($data) OR $data = (array) $data;

        // Merge in what we already have with the specific data
        $this->_data = array_merge($this->_data, $data);

        // We don't need you any more buddy
        unset($data);

        if (empty($this->_title))
        {
            $this->_title = $this->_guess_title();
        }

        //Render template module view
        //the javascripts and css
        $module_groups = array();
        foreach( $this->_module_groups as $name => $modules )
        {
            $module_groups[$name] = '';
            foreach ($modules as $module)
            {
                // If it uses a view, load it
                if (isset($module['module']) && !empty($module['module']))
                {
                    $code = $module['module'];
                    $class = 'Mod_' . $code;
                    $this->_ci->load->library('module');

                    include_once 'system/tomatocart/modules/' . $module['module'] . '/' . $module['module'] . EXT;

                    $obj = new $class($module['params']);

                    $module_groups[$name] .= $obj->index();
                }
            }
        }

        // Output template variables to the template
        $template['title']  = $this->_title;
        $template['breadcrumbs'] = $this->_breadcrumbs;
        $template['meta_tags'] = $this->get_meta_tags();
        $template['javascripts'] = $this->get_javascripts();
        $template['stylesheets'] = $this->get_stylesheets();

        $template['module_groups'] = $module_groups;

        // Assign by reference, as all loaded views will need access to modules
        $this->_data['template'] =& $template;

        // Disable sodding IE7's constant cacheing!!
        $this->_ci->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
        $this->_ci->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->_ci->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
        $this->_ci->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        $this->_ci->output->set_header('Pragma: no-cache');

        // Let CI do the caching instead of the browser
        $this->_ci->output->cache( $this->cache_lifetime );

        // Test to see if this file
        $this->_body = $this->_find_view( $view, array(), $this->_parser_body_enabled );

        // Want this file wrapped with a layout file?
        if ($this->_layout)
        {
            // Added to $this->_data['template'] by refference
            $template['body'] = $this->_body;

            // Find the main body and 3rd param means parse if its a template view (only if parser is enabled)
            $this->_body =  self::_load_view($this->_layout, $this->_data, TRUE, self::_find_view_folder());
        }

        // Want it returned or output to browser?
        if ( ! $return)
        {
            $this->_ci->output->set_output($this->_body);
        }

        return $this->_body;
    }

    /**
     * Set the title of the page
     *
     * @access  public
     * @param string
     * @return  void
     */
    public function title()
    {
        // If we have some segments passed
        if ($title_segments =& func_get_args())
        {
            $this->_title = implode($this->_title_separator, $title_segments);
        }

        return $this;
    }

    /**
     * Add head meta tags
     *
     * @access  public
     * @param  string $key key value
     * @param  string $value value
     * @return  void
     */
    public function add_meta_tags($key, $value) {
        $this->_meta_tags[$key][] = $value;

        return $this;
    }

    /**
     * Returns the tags of the page separated by a comma
     *
     * @access public
     * @return string
     */
    public function get_meta_tags() {
        $meta_tags = array();

        foreach ($this->_meta_tags as $key => $values) {
            $meta_tags[] = '<meta name="' . $key . '" content="' . implode(', ', $values) . '" />';
        }

        return implode("\n\t\t", $meta_tags);
    }

    /**
     * Adds a javascript file to link to
     *
     * @access public
     * @param string $filename The javascript filename to link to
     */
    function add_javascript_file($file) {
        if (!in_array($file, $this->_javascript_files)) {
            $this->_javascript_files[] = $file;
        }

        return $this;
    }

    /**
     * Returns the javascript filenames to link to on the page
     *
     * @access private
     * @return string
     */

    function get_javascript_files() {
        $files = array();

        foreach ($this->_javascript_files as $file) {
            $files[] = '<script language="javascript" type="text/javascript" src="' . $file . '"></script>';
        }

        return implode("\n\t\t", $files);
    }

    /**
     * Adds javascript logic to the page
     *
     * @param string $javascript The javascript block to add on the page
     * @access public
     */
    function add_javascript_block($block) {
        $this->_javascript_blocks[] = $block;

        return $this;
    }

    /**
     * Returns javascript blocks to add to the page
     *
     * @access private
     * @return string
     */

    function get_javascript_blocks() {
        return implode("\n\t\t", $this->_javascript_blocks);
    }

    /**
     * Returns the javascript to link from or embedd to on the page
     *
     * @access public
     * @return string
     */

    function get_javascripts() {
        $javascripts = '';
        if (!empty($this->_javascript_files)) {
            $javascripts .= $this->get_javascript_files();
        }

        if (!empty($this->_javascript_blocks)) {
            $javascripts .= $this->get_javascript_blocks();
        }

        return $javascripts;
    }

    /**
     * Adds a stylesheet to the page
     *
     * @param string  $url  URL to the style sheet
     */
    function add_stylesheet_file($file) {
        if ( !in_array($file, $this->_stylesheet_files) ) {
            $this->_stylesheet_files[] = $file;
        }
    }

    /**
     * Return the stylesheet linked to the page
     *
     * @return string
     */
    function get_stylesheet_files() {
        $files = array();

        if ( !empty($this->_stylesheet_files) ) {
            foreach ($this->_stylesheet_files as $file) {
                $files[] = '<link rel="stylesheet" type="text/css" href="' . $file . '" />' . "\n";
            }
        }

        return implode("\n\t\t", $files);
    }

    /**
     * Adds a stylesheet declaration to the page
     *
     * @param string  $content   Style declarations
     */
    function add_stylesheet_block($block) {
        $this->_stylesheet_blocks[] = $block;
    }

    /**
     * Return the stylesheet declaration
     *
     * @return string
     */
    function get_stylesheet_blocks() {
        $css = '<style type="text/css">' . "\n";

        if ( !empty($this->_stylesheet_blocks) ) {
            $css .= implode("\n", $this->_stylesheet_blocks);
        }

        $css .= '</style>' . "\n";

        return $css;
    }

    function get_stylesheets() {
        $style = '';
        if (!empty($this->_stylesheet_files)) {
            $style .= $this->get_stylesheet_files();
        }

        if (!empty($this->_stylesheet_blocks)) {
            $style .= $this->get_stylesheet_blocks();
        }

        return $style;
    }

    /**
     * Which template are we using here?
     *
     * @access  public
     * @param string  $template  Set a template for the template library to use
     * @return  void
     */
    public function set_template($template = NULL)
    {
        $data = $this->_ci->session->userdata('template');

        if ( ($template != NULL) || ($data === FALSE) ) {
            $set_template = ($template == NULL) ? config('DEFAULT_TEMPLATE') : $template;

            $data_default = array();

            $this->_ci->load->model('template_model');
            $templates = $this->_ci->template_model->get_templates();

            foreach ($templates as $template) {
                if ($template['code'] == config('DEFAULT_TEMPLATE')) {
                    $data_default = array('id' => $template['id'], 'code' => $template['code']);
                } elseif ($template['code'] == $set_template) {
                    $data = array('id' => $template['id'], 'code' => $template['code']);
                }
            }

            if (empty($data)) {
                $data =& $data_default;
            }

            $data =& $data_default;

            $this->_ci->session->set_userdata(array('template' => $data));
        }

        $this->_template = $data['code'];

        if ($this->_template AND file_exists($this->_templates_location . $this->_template))
        {
            $this->_template_id = $data['id'];
            $this->_template_path = rtrim($this->_templates_location . $this->_template . '/');
        }

        return $this;
    }

    /**
     * Get the current template path
     *
     * @access  public
     * @return  string The current template path
     */
    public function get_template_path()
    {
        return $this->_template_path;
    }


    /**
     * Which template layout should we using here?
     *
     * @access  public
     * @param string  $view
     * @return  void
     */
    public function set_layout($view, $_layout_subdir = '')
    {
        $this->_layout = $view;

        $_layout_subdir AND $this->_layout_subdir = $_layout_subdir;

        return $this;
    }

    /**
     * Set a view module
     * lei: add module should not include data
     *
     * @access  public
     * @param string
     * @param string
     * @param boolean
     * @return  void
     */
    public function add_module($group, $module)
    {
        /**updated by zheng lei , set_partical should add modules instead of set it.**/
        $this->_module_groups[$group][] = array('id' => $module['id'], 'module' => $module['module'], 'params' => $module['params']);
    }

    /**
     * Set the view modules
     * lei: add module should not include data
     *
     * @access  public
     * @param string
     * @param string
     * @param boolean
     * @return  void
     */
    public function add_modules($groups)
    {
        foreach ($groups as $key => $group) {
            foreach ($group as $module) {
                $this->add_module($key, $module);
            }
        }
    }

    /**
     * Helps build custom breadcrumb trails
     *
     * @access  public
     * @param string  $name   What will appear as the link text
     * @param string  $url_ref  The URL segment
     * @return  void
     */
    public function set_breadcrumb($name, $uri = '')
    {
        $this->_breadcrumbs[] = array('name' => $name, 'uri' => $uri);
        return $this;
    }

    public function get_breadcrumbs()
    {
        return $this->_breadcrumbs;
    }

    /**
     * Set a the cache lifetime
     *
     * @access  public
     * @param string
     * @param string
     * @param boolean
     * @return  void
     */
    public function set_cache($minutes = 0)
    {
        $this->cache_lifetime = $minutes;
        return $this;
    }

    /**
     * enable_parser
     * Should be parser be used or the view files just loaded normally?
     *
     * @access  public
     * @param  string $view
     * @return  void
     */
    public function enable_parser($bool)
    {
        $this->_parser_enabled = $bool;
        return $this;
    }

    /**
     * enable_parser_body
     * Should be parser be used or the body view files just loaded normally?
     *
     * @access  public
     * @param  string $view
     * @return  void
     */
    public function enable_parser_body($bool)
    {
        $this->_parser_body_enabled = $bool;
        return $this;
    }

    /**
     * templates_location
     * Get the location where template is stored
     *
     * @access  public
     * @param  string $view
     * @return  array
     */
    public function templates_location()
    {
        return $this->_templates_location;
    }

    /**
     * set_templates_location
     * Set location for templates to be looked in
     *
     * @access  public
     * @param  string $view
     * @return  array
     */
    public function set_templates_location($location)
    {
        $this->_templates_location = $location;
    }

    /**
     * template_exists
     * Check if a template exists
     *
     * @access  public
     * @param  string $view
     * @return  array
     */
    public function template_exists($template = NULL)
    {
        $template OR $template = $this->_template;

        if (is_dir($this->_templates_location . $template))
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * get_layouts
     * Get all current layouts (if using a template you'll get a list of template layouts)
     *
     * @access  public
     * @param  string $view
     * @return  array
     */
    public function get_layouts()
    {
        $layouts = array();

        foreach(glob(self::_find_view_folder() . 'layouts/*.*') as $layout)
        {
            $layouts[] = pathinfo($layout, PATHINFO_BASENAME);
        }

        return $layouts;
    }


    /**
     * get_layouts
     * Get all current layouts (if using a template you'll get a list of template layouts)
     *
     * @access  public
     * @param  string $view
     * @return  array
     */
    public function get_template_layouts($template = NULL)
    {
        $template OR $template = $this->_template;

        $layouts = array();

        // Get special web layouts
        if( is_dir($this->_templates_location . $template . '/views/web/layouts/') )
        {
            foreach(glob($this->_templates_location . $template . '/views/web/layouts/*.*') as $layout)
            {
                $layouts[] = pathinfo($layout, PATHINFO_BASENAME);
            }
            break;
        }

        // So there are no web layouts, assume all layouts are web layouts
        if(is_dir($this->_templates_location . $template . '/views/layouts/'))
        {
            foreach(glob($this->_templates_location . $template . '/views/layouts/*.*') as $layout)
            {
                $layouts[] = pathinfo($layout, PATHINFO_BASENAME);
            }
            break;
        }

        return $layouts;
    }

    /**
     * layout_exists
     * Check if a template layout exists
     *
     * @access  public
     * @param  string $view
     * @return  array
     */
    public function layout_exists($layout)
    {
        // If there is a template, check it exists in there
        if ( ! empty($this->_template) AND in_array($layout, self::get_template_layouts()))
        {
            return TRUE;
        }

        // Otherwise look in the normal places
        return file_exists(self::_find_view_folder().'layouts/' . $layout . self::_ext($layout));
    }

    // find layout files, they could be mobile or web
    private function _find_view_folder()
    {
        // Base view folder
        $view_folder = APPPATH.'views/';

        // Using a template? Put the template path in before the view folder
        if ( ! empty($this->_template))
        {
            $view_folder = $this->_template_path;
        }

        // Would they like the mobile version?
        if ($this->_is_mobile === TRUE AND is_dir($view_folder.'mobile/'))
        {
            // Use mobile as the base location for views
            $view_folder .= 'mobile/';
        }

        // Would they like the pad version?
        else if ($this->_is_pad === TRUE AND is_dir($view_folder.'pad/'))
        {
            // Use mobile as the base location for views
            $view_folder .= 'pad/';
        }

        // Use the web version
        else if (is_dir($view_folder.'web/'))
        {
            $view_folder .= 'web/';
        }

        // Things like views/admin/web/view admin = subdir
        if ($this->_layout_subdir)
        {
            $view_folder .= $this->_layout_subdir.'/';
        }

        // If using templates store this for later, available to all views
        //return $this->_ci->load->_ci_cached_vars['template_views'] = $view_folder;
        return $view_folder;
    }

    // A module view file can be overriden in a template
    public function _find_view($view, array $data, $parse_view = TRUE)
    {
        // Only bother looking in templates if there is a template
        if ( ! empty($this->_template))
        {
            $template_views = array(
            $this->_template . '/views/' . $this->_module . '/' . $view,
          'base/views/' . $view
            );

            if ($this->_is_pad)
            {
                $template_views = array(
                $this->_template . '/pad/views/' . $view,
          'base/pad/views/' . $view
                );
            } else if ($this->_is_mobile)
            {
                $template_views = array(
                $this->_template . '/mobile/views/' . $view,
          'base/mobile/views/' . $view
                );
            }
            else
            {
                $template_views = array(
                $this->_template . '/web/views/' . $view,
          'base/web/views/' . $view
                );
            }

            foreach ($template_views as $template_view)
            {
                if (file_exists($this->_templates_location . $template_view . self::_ext($template_view)))
                {
                    return self::_load_view($template_view, $this->_data + $data, $parse_view, $this->_templates_location);
                }
            }
        }

        // Not found it yet? Just load, its either in the module or root view
        return self::_load_view($view, $this->_data + $data, $parse_view);
    }

    // A module view file can be overriden in a template
    private function _find_module_view($view, array $data, $parse_view = TRUE)
    {
        // Only bother looking in templates if there is a template
        if ( ! empty($this->_template))
        {
            $template_views = array(
            $this->_template . '/modules/'. $view, //try to load the module view first from the template if the view is override
    			'base/modules/' . $view                //load the view from base
            );

            foreach ($template_views as $template_view)
            {
                if (file_exists($this->_templates_location . $template_view . self::_ext($template_view)))
                {
                    return self::_load_view($template_view, $data, $parse_view, $this->_templates_location);
                }
            }
        }

        // Not found it yet? Just load, its either in the module or root view
        return self::_load_view($view, $this->_data + $data, $parse_view);
    }

    private function _load_view($view, array $data, $parse_view = TRUE, $override_view_path = NULL)
    {
        // Sevear hackery to load views from custom places AND maintain compatibility with Modular Extensions
        if ($override_view_path !== NULL)
        {
            if ($this->_parser_enabled === TRUE AND $parse_view === TRUE)
            {
                // Load content and pass through the parser
                $content = $this->_ci->parser->parse_string($this->_ci->load->_ci_load(array(
                    '_ci_path' => $override_view_path.$view.self::_ext($view),
                    '_ci_vars' => $data,
                    '_ci_return' => TRUE
                )), $data, TRUE);
            }

            else
            {
                // Load it directly, bypassing $this->load->view() as ME resets _ci_view
                $content = $this->_ci->load->_ci_load(array(
                    '_ci_path' => $override_view_path.$view.self::_ext($view),
                    '_ci_vars' => $data,
                    '_ci_return' => TRUE
                ));
            }
        }

        // Can just run as usual
        else
        {
            // Grab the content of the view (parsed or loaded)
            $content = ($this->_parser_enabled === TRUE AND $parse_view === TRUE)

            // Parse that bad boy
            ? $this->_ci->parser->parse($view, $data, TRUE )

            // None of that fancy stuff for me!
            : $this->_ci->load->view($view, $data, TRUE );
        }

        return $content;
    }

    private function _guess_title()
    {
        $this->_ci->load->helper('inflector');

        // Obviously no title, lets get making one
        $title_parts = array();

        // If the method is something other than index, use that
        if ($this->_method != 'index')
        {
            $title_parts[] = $this->_method;
        }

        // Make sure controller name is not the same as the method name
        if ( ! in_array($this->_controller, $title_parts))
        {
            $title_parts[] = $this->_controller;
        }

        // Is there a module? Make sure it is not named the same as the method or controller
        if ( ! empty($this->_module) AND !in_array($this->_module, $title_parts))
        {
            $title_parts[] = $this->_module;
        }

        // Glue the title pieces together using the title separator setting
        $title = humanize(implode($this->_title_separator, $title_parts));

        return $title;
    }

    private function _ext($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) ? '' : '.php';
    }

    public function get_id() {
        return $this->_template_id;
    }

    public function get_code() {
        return $this->_template;
    }
}

// END Template class