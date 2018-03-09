<?php
/*
Plugin Name: AWSM Team
Plugin URI: http://awsm.in/team-pro-documentation
Description: The most versatile plugin to create and manage your Team page. Packed with 8 unique presets and number of styles to choose from.
Version: 1.1.3
Author: AWSM Innovations
Author URI: http://awsm.in/
License: GPL
Copyright: AWSM Innovations
*/

if (!defined('ABSPATH')) {
    exit;
}
// if direct access

if (!class_exists('Awsm_team_lite')): 
/**
 * Team main class
 * author : AWSM
 */ 
    class Awsm_team_lite
    {
        private static $instance = null;
        private $settings;
        
        /**
         * Creates or returns an instance of this class.
         * @since    1.0.0
         */
        public static function get_instance()
        {
            // If an instance hasn't been created and set to $instance create an instance and set it to $instance.
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct()
        {
            $this->settings = array(
                'plugin_path' => plugin_dir_path(__FILE__),
                'plugin_url' => plugin_dir_url(__FILE__),
                'plugin_base' => dirname(plugin_basename(__FILE__)),
                'plugin_file' => __FILE__,
                'plugin_version' => '1.1.3',
            );
            $this->load_plugin_textdomain();
            $this->run_plugin();
            $this->adminfunctions();
        }
        /**
         * Localisation
         * @since: 1.0
         */
        public function load_plugin_textdomain()
        {
            load_plugin_textdomain( 'awsm-team',FALSE, $this->settings['plugin_base'] . '/language/' );
        }
        /**
         * Main plugin function
         * @since: 1.0
         */
        public function run_plugin()
        {
            add_action('init', array( $this, 'create_member_support' ));
            add_action('init', array( $this, 'custom_image_size' ));
            add_shortcode('awsmteam', array( $this, 'awsmteam_shortcode' ));
            add_action('wp_enqueue_scripts', array( $this, 'embed_front_script_styles' ));
            add_action('wp_head',  array( $this, 'custom_css' ));
        }
        /**
         * Team custom css on theme head
         * @since: 1.0
         */
        function custom_css(){
            global $wp_query;   
            $posts = $wp_query->posts;
            $pattern  = '(awsmteam)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
            $snippet = '';
            $shortcodes = array();
            if ($posts) {
                foreach ($posts as $post){
                    $pattern = get_shortcode_regex();
                    preg_match('/\[awsmteam (.+?)\]/', $post->post_content, $matches);
                    if (is_array($matches) && isset($matches[1])) {
                        if(is_array(shortcode_parse_atts($matches[1]))){
                             $shortcodes[] = shortcode_parse_atts($matches[1]);
                        }
                    }

                    if(!empty($shortcodes)){
                        foreach ($shortcodes as $shortcode) {
                            if(isset($shortcode['id'])){
                                $custom_css = get_post_meta( $shortcode['id'], 'custom_css', true );
                                if($custom_css){
                                    $snippet.= $custom_css;
                                }
                            }
                            
                        }   
                    }
                }
                if($snippet){
                    printf('<style type="text/css">%s</style>',$snippet) ;
                }
            }
        }
        /**
         * Custom image size for team memebers
         * @since 1.0
         */
        public function custom_image_size()
        {
            if ( function_exists( 'add_image_size' ) ) {
                add_image_size('awsm_team', 500, 500, true);
            }
        }
        /**
         * AWSM team shortocde
         * @since 1.0
         */
        public function awsmteam_shortcode($atts)
        {
            extract(shortcode_atts(array(
                'id' => false
            ), $atts));
            $options = $this->get_options('awsm_team', $id);
            if (!$options) {
                return '<div class="awsm-team-error">' . __('Team not found', 'awsm-team') . '</div>';
            }
            if (empty($options['memberlist'])) {
                return '<div class="awsm-team-error">' . __('No members found', 'awsm-team') . '</div>';
            }
            $template = $this->settings['plugin_path'] . 'templates/' . $options['team-style'] . '.php';
            if (file_exists($template)) {
                ob_start();
                $teamargs = array(
                    'orderby' => 'post__in',
                    'post_type' => 'awsm_team_member',
                    'post__in' => $options['memberlist'],
                    'posts_per_page' => -1 ,
                );
                $team     = new WP_Query($teamargs);
                include $template;
                wp_reset_postdata();
                return ob_get_clean();
            }
        }
        /**
         * Register front scripts
         *  @since    1.0.0
         */
        public function embed_front_script_styles()
        {
            wp_enqueue_script('awsm-team', plugins_url('js/team.min.js', $this->settings['plugin_file']), array(
                'jquery'
            ), $this->settings['plugin_version'], true); 
            wp_enqueue_style('awsm-team', plugins_url('css/team.min.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
        }
        /**
         *  Create custom post type
         *  @since    1.0.0
         */
        public function create_member_support()
        {
            // Create awsm_team_member post type
            if (post_type_exists("awsm_team_member")) {
                return;
            }
            $singular = __('Team Member', 'awsm-team');
            $plural   = __('Team Members', 'awsm-team');
            $labels = array(
                'name' => $plural,
                'singular_name' => $singular,
                'menu_name' => __('AWSM Team', 'awsm-team'),
                'add_new' => __('Add New Member', 'awsm-team'),
                'add_new_item' => sprintf(__('Add %s', 'awsm-team'), $singular),
                'new_item' => sprintf(__('New %s', 'awsm-team'), $singular),
                'edit_item' => sprintf(__('Edit %s', 'awsm-team'), $singular),
                'view_item' => sprintf(__('View %s', 'awsm-team'), $singular),
                'all_items' => sprintf(__('Members', 'awsm-team')),
                'search_items' => sprintf(__('Search %s', 'awsm-team'), $plural),
                'not_found' => sprintf(__('No %s found', 'awsm-team'), $plural),
                'not_found_in_trash' => sprintf(__('No %s found in trash', 'awsm-team'), $plural)
            );
            $cp_args = array(
                'labels' => $labels,
                'description' => sprintf(__('This is where you can create and manage %s.', 'awsm-team'), $plural),
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'post',
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail'
                ),
                'menu_icon' => 'dashicons-admin-users'
            );
            register_post_type('awsm_team_member', $cp_args);
            if (post_type_exists("awsm_team")) {
                return;
            }
            $singular = __('Team', 'awsm-team');
            $plural   = __('Teams', 'awsm-team');
            $labels = array(
                'name' => $plural,
                'singular_name' => $singular,
                'menu_name' => __('Awsm Team', 'awsm-team'),
                'add_new' => __('Add Team', 'awsm-team'),
                'add_new_item' => sprintf(__('Add %s', 'awsm-team'), $singular),
                'new_item' => sprintf(__('New %s', 'awsm-team'), $singular),
                'edit_item' => sprintf(__('Edit %s', 'awsm-team'), $singular),
                'view_item' => sprintf(__('View %s', 'awsm-team'), $singular),
                'all_items' => sprintf(__('Teams', 'awsm-team')),
                'search_items' => sprintf(__('Search %s', 'awsm-team'), $plural),
                'not_found' => sprintf(__('No %s found', 'awsm-team'), $plural),
                'not_found_in_trash' => sprintf(__('No %s found in trash', 'awsm-team'), $plural)
            );
            $cp_args = array(
                'labels' => $labels,
                'description' => sprintf(__('This is where you can create and manage %s.', 'awsm-team'), $plural),
                'show_ui' => true,
                "show_in_menu" => 'edit.php?post_type=awsm_team_member',
                'capability_type' => 'post',
                'supports' => array(
                    'title'
                )
            );
            register_post_type('awsm_team', $cp_args);
        }
        /**
         * Initiate admin functions.
         * @since 1.0
         */
        public function adminfunctions()
        {
            if (is_admin()) {
                add_action('add_meta_boxes', array( $this, 'register_metaboxes' ));
                add_action('save_post', array( $this, 'save_metabox_data' ), 10, 3);
                add_action('admin_enqueue_scripts', array( $this, 'meta_box_scripts' ) , 10, 1 );
                add_action('admin_menu', array( $this, 'add_submenu_items' ), 12);
                add_action('edit_form_after_title', array( $this, 'shortcode_preview' ));
                add_filter('manage_awsm_team_member_posts_columns' , array( $this, 'custom_columns_member' ));
                add_action('manage_awsm_team_member_posts_custom_column' , array( $this, 'custom_columns_member_data' ) , 10, 2 );
                add_filter('manage_awsm_team_posts_columns' , array( $this, 'custom_columns_team' ));
                add_action('manage_awsm_team_posts_custom_column' , array( $this, 'custom_columns_team_data' ) , 10, 2 );  
                add_filter('admin_post_thumbnail_html', array($this,'image_help'));
                add_filter('admin_post_thumbnail_size',  array($this,'custom_admin_thumb_size'));
            }
        }
        /**
         * Custom thumbnail size for awsm_team_member
         * @since 1.0
         */
        function custom_admin_thumb_size($thumb_size){
            global $post_type,$post;
            if($post_type == 'awsm_team_member'){
                $thumb_size = "awsm_team";
            }
            return $thumb_size; 
        }
        /**
         * Image size help text
         * @since 1.0
         */
        function image_help($content){
            global $post_type,$post;
            if ($post_type == 'awsm_team_member') {
                if(!has_post_thumbnail( $post->ID )){
                   $content .= '<p>'.__('Please upload square-cropped photos with a minimum dimension of 500px','awsm-team').'</p>';
                }
            }
            return $content;
        }
        /**
         * Custom column on member table.
         * @since 1.0
         */
        function custom_columns_member($columns){
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __('Name','awsm-team'),
                'featured_image' => __('Photo','awsm-team'),
                'designation' => __('Designation','awsm-team'),
                'date' => 'Date'
             );
            return $columns;
        }
        /**
         * Custom member table data.
         * @since 1.0
         */
        function custom_columns_member_data($column,$post_ID){
            $options = $this->get_options('awsm_team_member',$post_ID );
            switch ( $column ) {
            case 'featured_image':
                echo the_post_thumbnail( 'thumbnail' );
                break;
            case 'designation':
                echo $options['awsm-team-designation'];
                break;
            }
        }
        /**
         * Custom member column for team.
         * @since 1.0
         */
        function custom_columns_team($columns){
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __('Name','awsm-team'),
                'members' => __('Members','awsm-team'),
                'preset' => __('Preset','awsm-team'),
                'style' => __('Style','awsm-team'),
                'shortcode' =>__('Shortcode','awsm-team')
             );
            return $columns;
        }
        /**
         * Custom member column data for team.
         * @since 1.0
         */
        function custom_columns_team_data($column,$post_ID){
            $options = $this->get_options('awsm_team',$post_ID );
            $post = get_post( $post_ID );
            switch ( $column ) {
            case 'members':
                echo count($options['memberlist']);
                break;
            case 'preset':
                echo $options['team-style'];
                break;
            case 'style':
                echo $options['preset'];
                break;
            case 'shortcode':
                printf('<code>[awsmteam id="%s"]</code>',$post_ID);
                break;
            }
        }
        /**
         * Shortcode preview on team edit page
         * @since 1.0
         */
        public function shortcode_preview($post)
        {
            if ('awsm_team' == $post->post_type && 'publish' == $post->post_status) {
                printf('<p>%1$s: <code>[awsmteam id="%2$s"]</code><button id="copy-awsm" type="button" data-clipboard-text="[awsmteam id=&quot;%2$s&quot;]" class="button">%3$s</button></p>', __("Shortcode", 'awsm-team'), $post->ID, __("Copy", 'awsm-team'));
            }
            return;
        }
        /**
         * Loads meta box helper scripts
         * since 1.0
         */
        public function meta_box_scripts($hook)
        {
            global $post;
            if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
                if( 'awsm_team_member' ==  $post->post_type or 'awsm_team' ==  $post->post_type){
                wp_enqueue_style('awsm-team-admin', plugins_url('css/admin.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
                wp_enqueue_script('team-meta-box', plugins_url('js/team-admin.js', $this->settings['plugin_file']), array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), $this->settings['plugin_version']);
                wp_enqueue_script('select2', plugins_url('js/select2.min.js', $this->settings['plugin_file']), array( 'jquery' ), $this->settings['plugin_version']);
                wp_enqueue_style('select2', plugins_url('css/select2.min.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
                wp_enqueue_style('awsm-team-icomoon-css', plugins_url('css/icomoon.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
               }
            }
            
        }
        /**
         * Adding submenu items
         *  @since    1.0.0
         */
        public function add_submenu_items()
        {
            add_submenu_page('edit.php?post_type=awsm_team_member', __('Add Team', 'awsm-team'), __('Add Team', 'awsm-team'), 'manage_options', 'post-new.php?post_type=awsm_team');
        }
        /**
         * Register meta box
         *  @since    1.0.0
         */
        public function register_metaboxes()
        {
            add_meta_box('member_details', __('Member Details', 'awsm-team'), array( $this, 'member_details_meta' ), 'awsm_team_member');
            add_meta_box('team_details', __('Team Details', 'awsm-team'), array( $this, 'team_details_meta' ), 'awsm_team', 'normal', 'high');
            add_meta_box('awsm_team_pro', __('Upgrade to AWSM Team Pro', 'awsm-team'), array( $this, 'pro_metabox' ), 'awsm_team', 'side', 'default');
        }
        public function pro_metabox(){
            include $this->settings['plugin_path'] . 'includes/pro-features.php';
        }
        /**
         * Meta box display callback - Member details.
         * @since    1.0.0
         * @param WP_Post $post Current post object.
         */
        public function member_details_meta($post)
        {
            wp_nonce_field(basename(__FILE__), 'awsm_meta_details');
            $awsm_social  = get_post_meta($post->ID, 'awsm_social', true);
            $socialicons  = array('mail', 'link', 'google-plus', 'google-plus2', 'hangouts', 'google-drive', 'facebook', 'facebook2', 'instagram', 'whatsapp', 'twitter', 'youtube', 'vimeo', 'vimeo2', 'flickr', 'flickr2', 'dribbble', 'behance', 'behance2', 'dropbox', 'wordpress', 'blogger', 'tumblr', 'tumblr2', 'skype', 'linkedin2', 'linkedin', 'stackoverflow', 'pinterest2', 'pinterest', 'foursquare','github', 'flattr', 'xing', 'xing2', 'stumbleupon', 'stumbleupon2', 'delicious', 'lastfm', 'lastfm2', 'hackernews', 'reddit', 'soundcloud', 'soundcloud2', 'yahoo', 'blogger2', 'ello', 'wordpress2', 'steam', 'steam2', '500px', 'deviantart', 'twitch', 'feed', 'feed2', 'sina-weibo', 'renren', 'vk', 'vine', 'telegram', 'spotify', 'mail2', 'mail3');
            include $this->settings['plugin_path'] . 'includes/member-details.php';
        }
        /**
         * Meta box display callback - Team details.
         * @since    1.0.0
         * @param WP_Post $post Current post object.
         */
        public function team_details_meta($post)
        {
            wp_nonce_field(basename(__FILE__), 'awsm_meta_details');
            $args         = array(
                'post_type' => 'awsm_team_member',
                'posts_per_page' => -1
            );
            $members      = new WP_Query($args);
            $options      = $this->get_options('awsm_team', $post->ID);
            $defaultimage = $this->settings['plugin_url'] . 'images/default-user.png';
            include $this->settings['plugin_path'] . 'includes/team-details.php';
        }
        /**
         * Save metabox
         * @param  Int $post_id id of the post
         * @param  Object $post Post Object
         * @since 1.0
         */
        public function save_metabox_data($post_id, $post)
        {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (!isset($_POST['awsm_meta_details']) || !wp_verify_nonce($_POST['awsm_meta_details'], basename(__FILE__))) {
                return $post_id;
            }
            $post_type = get_post_type_object($post->post_type);
            if (!current_user_can($post_type->cap->edit_post, $post_id)) {
                return $post_id;
            }
            if ($post->post_type == 'awsm_team_member') {
                $team_repeater = array(
                    'awsm_social' => array(
                        'icon' => 'awsm-team-icon',
                        'link' => 'awsm-team-link'
                    )
                );
                $team_meta = array(
                    'awsm-team-designation',
                    'awsm-team-short-desc'
                );
                foreach ($team_repeater as $key => $value) {
                    $olddata = get_post_meta($post_id, $key, true);
                    $newdata = $item = array();
                    foreach ($value as $k => $v) {
                        $item[$k] = $_POST[$v];
                    }
                    $count = count(reset($item));
                    for ($i = 0; $i < $count; $i++) {
                        foreach ($value as $k => $v) {
                            if ($item[$k][$i] != '') {
                                $newdata[$i][$k] = stripslashes(strip_tags($item[$k][$i]));
                            }
                        }
                    }
                    if (!empty($newdata) && $newdata != $olddata) {
                        update_post_meta($post_id, $key, $newdata);
                    } elseif (empty($newdata) && $olddata) {
                        delete_post_meta($post_id, $key, $olddata);
                    }
                    
                }
            } elseif ($post->post_type == 'awsm_team') {
                $team_meta = array('memberlist', 'team-style', 'preset', 'columns', 'custom_css');
            }
            foreach ($team_meta as $meta_key) {
                $olddata = get_post_meta($post_id, $meta_key, true);
                $newdata = array();
                if (isset($_POST[$meta_key])) {
                    if (is_array($_POST[$meta_key])) {
                        $newdata = $_POST[$meta_key];
                    } else {
                        $newdata = stripslashes(strip_tags($_POST[$meta_key]));
                    }
                    if (!empty($newdata) && $newdata != $olddata) {
                        update_post_meta($post_id, $meta_key, $newdata);
                    } elseif (empty($newdata) && $olddata) {
                        delete_post_meta($post_id, $meta_key, $olddata);
                    }
                } else {
                    delete_post_meta($post_id, $meta_key, $olddata);
                }
            }
        }
        /**
         * Dropdown Builder
         * @since   1.0
         */
        public function selectbuilder($name, $options, $selected = "", $selecttext = "", $class = "", $optionvalue = 'value')
        {
            if (is_array($options)):
                $select_html = "<select name=\"$name\" id=\"$name\" class=\"$class\">";
                if ($selecttext) {
                    $select_html .= '<option value="">' . $selecttext . '</option>';
                }
                foreach ($options as $key => $option) {
                    if ($optionvalue == 'value') {
                        $value = $option;
                    } else {
                        $value = $key;
                    }
                    $select_html .= "<option value=\"$value\"";
                    if ($value == $selected) {
                        $select_html .= ' selected="selected"';
                    }
                    $select_html .= ">$option</option>\n";
                }
                $select_html .= '</select>';
                echo $select_html;
            else:
            endif;
        }
        /**
         * Get options
         * @param  String $postype Post type slug
         * @param  Int $post_id ID of post
         * @since   1.0
         */
        public function get_options($postype, $post_id)
        {
            $post = get_post($post_id);
            
            if (!$post) {
                return false;
            }
            
            $metakeys['awsm_team_member'] = array(
                'awsm_social',
                'awsm-team-designation',
                'awsm-team-short-desc'
            );
            $metakeys['awsm_team']        = array(
                'memberlist',
                'team-style',
                'preset',
                'columns',
                'custom_css'
            );
            $options['awsm_team_member']  = array(
                'awsm_social' => array(),
                'awsm-team-designation' => '',
                'awsm-team-short-desc' => ''
            );
            $options['awsm_team']         = array(
                'memberlist' => array(),
                'team-style' => 'cards',
                'preset' => '',
                'columns' => '',
                'custom_css' => ''
            );
            foreach ($metakeys[$postype] as $key => $value) {
                $metavalue = get_post_meta($post_id, $value, true);
                if ($metavalue) {
                    $options[$postype][$value] = $metavalue;
                }
            }
            return $options[$postype];
        }
        /**
         * Get team thumbnail
         * @param  Int $team_id  Post id of tram
         * @param  string $thumbnail thumbnail size
         * @since   1.0
         */
        public function team_thumbnail($team_id, $thumbnail = "awsm_team")
        {
            $defaultimage = $this->settings['plugin_url'] . 'images/default-user.png';
            $member_image = get_post_thumbnail_id($team_id);
            if ($member_image) {
                $member_image_url = wp_get_attachment_image_src($member_image, $thumbnail, true);
                $member_image_url = $member_image_url[0];
            } else {
                $member_image_url = $defaultimage;
            }
            return $member_image_url;
        }
        /**
         * Item stle generator
         * @since   1.0
         */
        public function item_style($options, $custom = "")
        {
            $style = array(
                $options['team-style'] . '-style',
                $options['preset'],
                'grid-' . $options['columns'] . '-col',
                $custom
            );
            return implode(' ', $style);
        }
        /**
         * Class generator
         * @param  Array $class classnames
         * @since   1.0
         */
        public function addclass($class)
        {
            return implode(' ', $class);
        }
        /**
         * ID generator
         * @param  Array $id 
         * @since   1.0
         */
        public function add_id($id)
        {
            return implode('-', $id);
        }
        /**
         * Print the meta data after checking it's existence
         * @since   1.0
         */
        public function checkprint($template, $value, $return = false)
        {
            if ($value) {
                if ($return) {
                    return sprintf($template, $value);
                } else {
                    echo sprintf($template, $value);
                }
                
            }
        }
    }
function awms_team_activation(){
    if ( !class_exists('Awsm_team') ) {
        Awsm_team_lite::get_instance();
    }
}
function awms_team__disable_self(){
    if ( class_exists( 'Awsm_team' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        add_action( 'admin_notices', 'awms_team_disable_notice' );
    }
}
function awms_team_disable_notice(){
    $class = 'notice is-dismissible notice-warning';
    $message = __( 'Thanks for upgrading AWSM Team Pro! The free version ‘AWSM Team’ has now been deactivated.', 'awsm-team' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}
// Plugin activation hook
add_action( 'plugins_loaded', 'awms_team_activation' );
add_action( 'admin_init', 'awms_team__disable_self' );
endif;  