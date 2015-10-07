<?php
/**
 * CoolCalc
 *
 * @package   CoolCalc
 * @author    Sander Baas <sander@implode.nl>
 * @license   GPL-2.0+
 * @link      https://implode.nl/coolcalc
 * @copyright 2015 Sander Baas
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-coolcalc-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package CoolCalc
 * @author  Sander Baas <sander@implode.nl>
 */
class CoolCalc {

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   1.0.0
   *
   * @var     string
   */
  const VERSION = '1.0.0';

  /**
   * @TODO - Rename "plugin-name" to the name of your plugin
   *
   * Unique identifier for your plugin.
   *
   *
   * The variable name is used as the text domain when internationalizing strings
   * of text. Its value should match the Text Domain file header in the main
   * plugin file.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'coolcalc';

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct() {
    // Load plugin text domain
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    // Activate plugin when new blog is added
    add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

    // Load public-facing style sheet and JavaScript.
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

    /* Define custom functionality.
     * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */
    add_action( 'init', array( $this, 'register_taxonomies_posttypes' ) );
    add_action( 'init', array( $this, 'init_sale_rewrite_rule' ) );

    add_action( 'admin_menu', array( $this, 'admin_menu' ) );

    add_action( 'save_post', array( $this, 'save_product_meta' ) );
    add_action( 'add_meta_boxes', array( $this, 'add_product_meta' ) );
    add_action( 'do_meta_boxes', array( $this, 'add_product_image' ) );
    add_filter( 'manage_cc_product_posts_columns', array($this,'manage_cc_product_posts_columns'));
    add_action( 'manage_cc_product_posts_custom_column', array($this,'manage_cc_product_posts_custom_column'), 10, 2 );
    add_filter( 'manage_edit-cc_product_sortable_columns', array($this, 'manage_edit_cc_product_sortable_columns'));
    
    add_filter( 'wp_seo_get_bc_ancestors', array($this,'wp_seo_get_bc_ancestors'),10,1);
    add_filter( 'wpseo_breadcrumb_links', array($this,'wpseo_breadcrumb_links'),10,1);
    
    add_filter( 'the_content_more_link', array($this, 'the_content_more_link'),10,2);

    add_filter( 'the_permalink', array($this, 'the_permalink'));

    add_action( 'the_post', array($this, 'the_post' ));
    add_filter( 'query_vars', array($this,'query_vars') );

    add_filter( 'pre_get_posts',array($this, 'pre_get_posts'));

    add_filter( 'posts_join', array($this, 'posts_join'));
    add_filter( 'posts_where', array($this, 'posts_where'));
    add_filter( 'posts_groupby', array($this, 'posts_groupby'));

    add_filter( 'post_row_actions', array($this,'remove_row_actions'), 10, 2 );

    add_shortcode( 'coolcalc_model_list', array( $this, 'shortcode_model_list' ) );
    add_shortcode( 'coolcalc_calculator', array( $this, 'shortcode_calculator' ) );

    if ( is_admin() ) {
      add_action( 'wp_ajax_cc_get_models', array($this, 'ajax_get_models') );
      add_action( 'wp_ajax_nopriv_cc_get_models', array($this, 'ajax_get_models') );
      add_action( 'wp_ajax_cc_get_options', array($this, 'ajax_get_options') );
      add_action( 'wp_ajax_nopriv_cc_get_options', array($this, 'ajax_get_options') );
      add_action( 'wp_ajax_cc_get_install_options', array($this, 'ajax_get_install_options') );
      add_action( 'wp_ajax_nopriv_cc_get_install_options', array($this, 'ajax_get_install_options') );
      add_action( 'wp_ajax_cc_get_pdf', array($this, 'ajax_get_pdf') );
      add_action( 'wp_ajax_nopriv_cc_get_pdf', array($this, 'ajax_get_pdf') );
    }
  }

  /**
   * Return the plugin slug.
   *
   * @since    1.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_plugin_slug() {
    return $this->plugin_slug;
  }

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {

    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Fired when the plugin is activated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate( $network_wide ) {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

      if ( $network_wide  ) {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {

          switch_to_blog( $blog_id );
          self::single_activate();
        }

        restore_current_blog();

      } else {
        self::single_activate();
      }

    } else {
      self::single_activate();
    }

  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public static function deactivate( $network_wide ) {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

      if ( $network_wide ) {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {

          switch_to_blog( $blog_id );
          self::single_deactivate();

        }

        restore_current_blog();

      } else {
        self::single_deactivate();
      }

    } else {
      self::single_deactivate();
    }

  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    1.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  public function activate_new_site( $blog_id ) {

    if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
      return;
    }

    switch_to_blog( $blog_id );
    self::single_activate();
    restore_current_blog();

  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    1.0.0
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids() {

    global $wpdb;

    // get an array of blog ids
    $sql = "SELECT blog_id FROM $wpdb->blogs
      WHERE archived = '0' AND spam = '0'
      AND deleted = '0'";

    return $wpdb->get_col( $sql );

  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    1.0.0
   */
  private static function single_activate() {
    // @TODO: Define activation functionality here
  }

  /**
   * Fired for each blog when the plugin is deactivated.
   *
   * @since    1.0.0
   */
  private static function single_deactivate() {
    // @TODO: Define deactivation functionality here
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    if(!session_id()) {
        session_start();
    }

    $domain = $this->plugin_slug;
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
    load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

  }

  /**
   * Register and enqueue public-facing style sheet.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
  }

  /**
   * Register and enqueues public-facing JavaScript files.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    global $post;
    // todo: get setting page id
    if (is_page('11') || (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'coolcalc_model_list'))) {
      wp_enqueue_script( $this->plugin_slug . '-mustache', plugins_url( 'assets/js/mustache.min.js', __FILE__ ), array('jquery') );
      wp_enqueue_script( $this->plugin_slug . '-number', plugins_url( 'assets/js/jquery.number.min.js', __FILE__ ), array('jquery') );
      wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
      $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
      $selected_model = get_query_var( 'cc_model', 0 );

      $params = array(
        'options' => array(
          'ajaxurl' => admin_url('admin-ajax.php', $protocol),
          'plugins_url' => plugins_url('coolcalc'),
          'selected_model' => $selected_model
        ),
        'l10n' => array(
          'model' => __('Model', 'coolcalc'),
          'capacity' => __('Capacity', 'coolcalc'),
          'price_excl' => __('Price excl. Tax', 'coolcalc'),
          'machine_options' => __('Machine options', 'coolcalc'),
          'install_options' => __('Install options', 'coolcalc'),
          'select_install_options_q' => __('I would also like a calculation for the replacement of existing units', 'coolcalc')
        )
      );
      wp_localize_script($this->plugin_slug . '-plugin-script', 'params', $params);
    }
  }

  /**
   * NOTE:  Actions are points in the execution of a page or process
   *        lifecycle that WordPress fires.
   *
   *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
   *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
   *
   * @since    1.0.0
   */
  public function action_method_name() {
    // @TODO: Define your action hook callback here
  }

  public function enqueue_admin_styles() {

    wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), self::VERSION );

  }

  public function enqueue_admin_scripts() {

    wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), self::VERSION );
    wp_enqueue_script( $this->plugin_slug . '-plugin-script');
  }

  function query_vars( $vars ){
    $vars[] = "cc_model";
    return $vars;
  }

  function posts_join ($join){
      global $pagenow, $wpdb;
      // I want the filter only when performing a search on edit page
      if ( is_admin() && $pagenow=='edit.php' && isset($_GET['s']) && $_GET['s'] != '') {
          $join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
      }
      return $join;
  }


  function posts_where( $where ){
      global $pagenow, $wpdb;
      // I want the filter only when performing a search on edit page
      if ( is_admin() && $pagenow=='edit.php' && isset($_GET['s']) && $_GET['s'] != '') {
          $where = preg_replace(
         "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
         "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
      }
      return $where;
  }

  function posts_groupby( $groupby ){
      global $pagenow, $wpdb;
      // I want the filter only when performing a search on edit page
      if ( is_admin() && $pagenow=='edit.php' && isset($_GET['s']) && $_GET['s'] != '') {
          $groupby = $wpdb->posts . '.ID';
      }
      return $groupby;
  }

  function pre_get_posts($query) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');
    if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'cc_product' && $orderby == '') {
      $query->set('orderby','code');
      $query->set('order','asc');
    }
    return $query;
  }

  function wp_seo_get_bc_ancestors($crumbs) {
    return $crumbs;
  }

  function wpseo_breadcrumb_links($links) {
    return $links;
  }

  function the_content_more_link($link,$link_text) {
    $location = get_query_var('location');
    $postcode = get_query_var('postcode');

    if ($location <> '' || $postcode <> '') {
      preg_match('/href="([^\#]*)\#([^"]*)"/i',$link, $matches);
      $url = $matches[1];
      $oldurl = $url;
      $question = true;
      if ($location <> '') {
        $url .= $question===true ? '?':'&';
        if ($question===true) { $question=false;}
        $url .= 'location='.$location;
      }
      if ($postcode <> '') {
        $url .= $question===true ? '?':'&';
        if ($question===true) { $question=false;}
        $url .= 'postcode='.$postcode;
      }
      $link = str_replace($oldurl, $url, $link);
    }
    return $link;
  }

  function the_permalink($url) {
    $qs = $_GET;
    unset($qs['s']);
    unset($qs['lang']);
    return add_query_arg($qs, $url);
  }

  function the_post( $post ) {
    $t = '';
    switch ($post->post_type) {
      case 'cc_product': $t = _x('cc_product','post_type label','coolcalc'); break;
    }
    $post->post_type_label = $t;
    return $post;
  }

  public function admin_menu() {
  }

  /**
   * Register custom taxonomies
   *
   * @since    1.0.0
   */
  public function register_taxonomies_posttypes() {
    wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), self::VERSION );

    register_post_type( 'cc_product',
      array(
        'labels' => array(
          'name' => __( 'Products' ,'coolcalc'),
          'singular_name' => __( 'Product' ,'coolcalc'),
          'add_new' => __('Add New','coolcalc'),
          'add_new_item' => __('Add New Product','coolcalc'),
          'edit' => __('Edit','coolcalc'),
          'edit_item' => __('Edit Product','coolcalc'),
          'new_item' => __('New Product','coolcalc'),
          'view' => __('View','coolcalc'),
          'view_item' => __('View Product','coolcalc'),
          'search_items' => __('Search Products','coolcalc'),
          'not_found' => __('No Products found','coolcalc'),
          'not_found_in_trash' => __('No Products found in Trash','coolcalc'),
          'parent' => __('Parent Product','coolcalc')
        ),
        'rewrite' => array('slug' => 'producten'),
        'query_var' => 'product',
        'show_ui' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'taxonomies' => array( ),
        'has_archive' => true
      )
    );

    register_post_type( 'cc_product_text',
      array(
        'labels' => array(
          'name' => __( 'Product Texts' ,'coolcalc'),
          'singular_name' => __( 'Product Text' ,'coolcalc'),
          'add_new' => __('Add New','coolcalc'),
          'add_new_item' => __('Add New Product Text','coolcalc'),
          'edit' => __('Edit','coolcalc'),
          'edit_item' => __('Edit Product Text','coolcalc'),
          'new_item' => __('New Product Text','coolcalc'),
          'view' => __('View','coolcalc'),
          'view_item' => __('View Product Text','coolcalc'),
          'search_items' => __('Search Product Texts','coolcalc'),
          'not_found' => __('No Product Texts found','coolcalc'),
          'not_found_in_trash' => __('No Product Texts found in Trash','coolcalc'),
          'parent' => __('Parent Product Text','coolcalc')
        ),
        'rewrite' => array('slug' => 'productteksten'),
        'query_var' => 'product_text',
        'show_ui' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'supports' => array( 'title', 'editor'),
        'taxonomies' => array( ),
        'has_archive' => false
      )
    );
  }

  function init_sale_rewrite_rule(){
    add_rewrite_rule(
      'producten/aanbiedingen',
      'index.php?post_type=cc_product&sale=1',
      'top' );
  }

  private static function display_meta_box($content) {
    include_once('views/meta_box.php');
  }

  private static function generate_meta_input($type, $name, $value, $label, $flags=array(), $howto=false) {
    foreach ($flags as $flag) ${'flag_'.$flag} = true;

    switch ($type) {
      default: $view = 'views/meta_input.php'; break;
      case 'url': $view = 'views/meta_input_url.php'; break;
      case 'email': $view = 'views/meta_input_email.php'; break;
      case 'textarea': $view = 'views/meta_textarea.php'; break;
      case 'price': $view = 'views/meta_input_price.php'; break;
      case 'select': $view = 'views/meta_select.php'; break;
      case 'advanced-select':
        $unique_parents = array();
        foreach ($value as $option) {
          $unique_parents[$option['parent_slug']] = $option['parent_label'];
        }
        
        $view = 'views/meta_advanced_select.php';
        break;
      case 'checkbox': $view = 'views/meta_checkbox.php'; break;
      case 'checkboxes': $view = 'views/meta_checkboxes.php'; break;
    }
    $result = '';
    ob_start();
    include($view);
    $result .= ob_get_clean();
    return $result;
  }

  private static function display_tag_meta_input($type, $name, $value, $label, $flags=array(), $howto=false) {
    foreach ($flags as $flag) ${'flag_'.$flag} = true;

    switch ($type) {
      default: $view = 'views/tag_meta_input.php'; break;
      case 'textarea': $view = 'views/tag_meta_textarea.php'; break;
      case 'select': $view = 'views/tag_meta_select.php'; break;
      case 'checkboxes': $view = 'views/tag_meta_checkboxes.php'; break;
    }

    include($view);
  }

  /**
   * CC CATEGORIES
   **/

  function cc_category_edit_form_fields($tag) {
    // Check for existing taxonomy meta for the term
    $t_id = $tag->term_id;
    $meta = get_option( "taxonomy_term_$t_id" );
  }

  function edited_cc_category( $term_id ) {
    if ( $_POST['taxonomy'] != 'cc_category' ) {
      return;
    }
  }

  function manage_edit_cc_category_columns($columns){
    $columns['posts'] = __('Items','coolcalc');
    return $columns;
  }
  
  function manage_cc_category_custom_column($out, $column_name, $theme_id){
    return $out; 
  }

  function pre_cc_category_description($value) {
    $value = str_replace('&nbsp;','',$value);
    return $value;
  }

  /**
   * CC PRODUCTS
   **/
  public function manage_cc_product_posts_columns( $columns ) {
    unset($columns['date']);
    $columns['index'] = __('Index', 'coolcalc');
    $columns['code'] = __('Product code', 'coolcalc');
    $columns['producttype'] = __('Product type', 'coolcalc');
    $columns['machine'] = __('Machine', 'coolcalc');
    $columns['text'] = __('Alternative text', 'coolcalc');
    $columns['capacity'] = __('Cooling capacity', 'coolcalc');
    $columns['default'] = __('Default', 'coolcalc');
    $columns['price'] = __('Price', 'coolcalc');
    return $columns;
  }

  public function manage_cc_product_posts_custom_column( $column_name, $post_id ) {
    $post = get_post($post_id);

    if ($column_name == 'description') {
      return $post->decription;
    }

    $suffix = '';
    $prefix = '';
    if (in_array($column_name, array('producttype','capacity','default','code','price','index'))) {
      $value = get_post_meta( $post_id, $column_name, true);
      if ($column_name == 'capacity' && $value > 0) { $suffix = 'kW'; }
      if ($column_name == 'price' && $value > 0) { $prefix = '&euro; '; }
      if ($column_name == 'default') { $value = $value == '1' ? 'yes' : 'no'; }
      echo $prefix._x($value,'coolcalc',$column_name).$suffix;
      return;
    }

    if ($column_name == 'machine') {
      $value = get_post_meta( $post_id, $column_name, true);
      if ($value > 0) {
        $machine = get_post($value);
        echo $machine->post_title;
      }
    }

    if ($column_name == 'text') {
      $value = get_post_meta( $post_id, $column_name, true);
      if ($value > 0) {
        $text = get_post($value);
        echo $text->post_title;
      }
    }
  }

  public function manage_edit_cc_product_sortable_columns($sortable_columns) {
    $sortable_columns['index'] = 'index';
    $sortable_columns['code'] = 'code';
    $sortable_columns['producttype'] = 'producttype';
    $sortable_columns['text'] = 'text';
    $sortable_columns['machine'] = 'machine';
    $sortable_columns['capacity'] = 'capacity';
    $sortable_columns['default'] = 'default';
    $sortable_columns['price'] = 'price';
    return $sortable_columns;
  }

  public function add_product_image() {
    remove_meta_box( 'postimagediv', 'cc_product', 'side' );
    add_meta_box('postimagediv', __('Image'), 'post_thumbnail_meta_box', 'cc_product', 'normal', 'high');
  }

  public function add_product_meta() {
    add_meta_box( 'care-product-meta',
      __('Product Details','coolcalc'),
      array( $this, 'display_product_meta' ),
      'cc_product',
      'normal',
      'high'
    );
  }

  public function display_product_meta($post) {
    $content = '';

    // index
    $content .= self::generate_meta_input(
      'text',
      'cc_product_index',
      get_post_meta( $post->ID, 'index', true),
      __('Index','coolcalc')
    );

    // code
    $content .= self::generate_meta_input(
      'text',
      'cc_product_code',
      get_post_meta( $post->ID, 'code', true),
      __('Product code','coolcalc')
    );

    // producttype
    $selected = get_post_meta( $post->ID, 'producttype', true);
    $options = array();
    $options[] = array('label'=>_x('machine','coolcalc','producttype'), 'value'=>'machine', 'selected'=>('machine' == $selected));
    $options[] = array('label'=>_x('machine-option','coolcalc','producttype'), 'value'=>'machine-option', 'selected'=>('machine-option' == $selected));
    $options[] = array('label'=>_x('install-option','coolcalc','producttype'), 'value'=>'install-option', 'selected'=>('install-option' == $selected));

    $content .= self::generate_meta_input(
      'select',
      'cc_product_producttype',
      $options,
      __('Product type','coolcalc')
    );

    // text
    $selected = get_post_meta( $post->ID, 'text', true);
    $options = array();
    $texts = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product_text',
      'order_by'=>'title'
    ));
    foreach($texts as $text) {
      $sel = $selected == $text->ID;
      $options[] = array('label'=>$text->post_title, 'value'=>$text->ID, 'selected'=>$sel);
    }
    $content .= self::generate_meta_input(
      'select',
      'cc_product_text',
      $options,
      _x('Text','coolcalc','remote product text'),
      array('optional')
    );

    // default
    $content .= self::generate_meta_input(
      'checkbox',
      'cc_product_default',
      get_post_meta( $post->ID, 'default', true),
      __('Default','coolcalc'),
      array(),
      __('Should this option be pre-selected?')
    );

    // machine
    $selected = get_post_meta( $post->ID, 'machine', true);
    $options = array();
    $machines = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product',
      'meta_key'=>'producttype',
      'meta_value'=>'machine',
      'order_by'=>'title'
    ));
    foreach($machines as $machine) {
      $sel = $selected == $machine->ID;
      $options[] = array('label'=>$machine->post_title, 'value'=>$machine->ID, 'selected'=>$sel);
    }
    $content .= self::generate_meta_input(
      'select',
      'cc_product_machine',
      $options,
      _x('Machine','coolcalc','parent machine for options')
    );

    // capacity
    $content .= self::generate_meta_input(
      'text',
      'cc_product_capacity',
      get_post_meta( $post->ID, 'capacity', true),
      __('Cooling capacity','coolcalc'),
      array(),
      __('Cooling capacity in kW, only for machines')
    );

    // price
    $content .= self::generate_meta_input(
      'price',
      'cc_product_price',
      get_post_meta( $post->ID, 'price', true),
      __('Price','coolcalc')
    );


    self::display_meta_box($content);
  }

  public function save_product_meta($post_id) {
    if ( !isset($_POST['post_type']) || $_POST['post_type'] != 'cc_product' ) {
      return;
    }

    foreach (array('index', 'code', 'producttype', 'text', 'machine', 'capacity', 'default', 'price') as $field) {
      $value = null;

      if (isset($_POST['cc_product_'.$field])) {
        switch($field) {
          default:
            $value = strip_tags( $_POST['cc_product_'.$field] );
            break;
        }
      }

      update_post_meta( $post_id, $field, $value);
    }
  }

  function remove_row_actions( $actions, $post ) {
    global $current_screen;
    if( substr($current_screen->post_type, 0, 3) !== 'cc_') return $actions;
    unset( $actions['inline hide-if-no-js'] );

    return $actions;
  }

  /** AJAX FUNCTIONS **/
  function ajax_get_models() {
    $machines = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product',
      'meta_query'=> array(
        array(
          'key'=>'producttype',
          'value'=>'machine'
        )
      ),
      'orderby'=>'meta_value_num',
      'meta_key'=>'capacity',
      'order'=>'ASC'
    ));

    foreach($machines as $machine) {
      $machine->meta = get_post_meta($machine->ID);
      if (isset($machine->meta['_thumbnail_id'][0]) && $machine->meta['_thumbnail_id'][0] <> '') {
        $image_data = wp_get_attachment_image_src( $machine->meta['_thumbnail_id'][0], array(300,300) );
        $machine->image = $image_data[0];
      }
    }

    header("HTTP/1.0 200 OK");
    header('Content-Type: application/json');
    echo json_encode($machines);
    die();
  }

  function ajax_get_options() {
    $options = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product',
      'meta_query' => array(
        array(
          'key' => 'producttype',
          'value' => 'machine-option'
        ),
        array(
          'key' => 'machine',
          'value' => $_POST['machine']
        ),
      ),
      'orderby'=>'meta_value_num',
      'meta_key'=>'index',
      'order'=>'ASC'
    ));

    foreach($options as $option) {
      $option->meta = get_post_meta($option->ID);
      // check for alternative post_content
      if (isset($option->meta['text'][0])) {
        $text_post = get_post($option->meta['text'][0]);
        $option->post_content = $text_post->post_content;
      }
      $option->post_content_html = nl2br($option->post_content);
    }

    header("HTTP/1.0 200 OK");
    header('Content-Type: application/json');
    echo json_encode($options);
    die();
  }

  function ajax_get_install_options() {
    $installOptions = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product',
            'meta_query' => array(
        array(
          'key' => 'producttype',
          'value' => 'install-option'
        ),
        array(
          'key' => 'machine',
          'value' => $_POST['machine']
        ),
      ),
      'orderby'=>'meta_value_num',
      'meta_key'=>'index',
      'order'=>'ASC'
    ));

    foreach($installOptions as $installOption) {
      $installOption->meta = get_post_meta($installOption->ID);
      // check for alternative post_content
      if (isset($installOption->meta['text'][0])) {
        $text_post = get_post($installOption->meta['text'][0]);
        $installOption->post_content = $text_post->post_content;
      }
      $installOption->post_content_html = nl2br($installOption->post_content);
    }

    header("HTTP/1.0 200 OK");
    header('Content-Type: application/json');
    echo json_encode($installOptions);
    die();
  }

  function ajax_get_pdf() {
    $modelPrice = 0;
    $optionTotal = 0;
    $installOptionTotal = 0;
    $totalPrice = 0;
    $tax = 0;
    $totalInclPrice = 0;

    $machine = get_post($_GET['machine']);
    $machine->meta = get_post_meta($machine->ID);
    if (isset($machine->meta['_thumbnail_id'][0]) && $machine->meta['_thumbnail_id'][0] <> '') {
      $image_data = wp_get_attachment_image_src( $machine->meta['_thumbnail_id'][0], array(300,300) );
      $machine->image = $image_data[0];
    }
    $modelPrice = $machine->meta['price'][0];
    $totalPrice += $modelPrice;

    $options = array();
    if ($_GET['options'] <> '') {
      $options = get_posts(array(
        'posts_per_page' => -1,
        'post_type'=>'cc_product',
        'meta_query' => array(
          array(
            'key' => 'producttype',
            'value' => 'machine-option'
          ),
          array(
            'key' => 'machine',
            'value' => $_GET['machine']
          ),
        ),
        'include'=>$_GET['options'],
        'orderby'=>'meta_value_num',
        'meta_key'=>'index',
        'order'=>'ASC'
      ));
    }

    foreach($options as $option) {
      $option->meta = get_post_meta($option->ID);
      // check for alternative post_content
      if (isset($option->meta['text'][0])) {
        $text_post = get_post($option->meta['text'][0]);
        $option->post_content = $text_post->post_content;
      }
      $option->post_content_html = nl2br($option->post_content);
      $optionTotal += $option->meta['price'][0];
    }

    $totalPrice += $optionTotal;

    $installOptions = array();
    if ($_GET['install_options'] <> '') {
      $installOptions = get_posts(array(
        'posts_per_page' => -1,
        'post_type'=>'cc_product',
        'meta_query' => array(
          array(
            'key' => 'producttype',
            'value' => 'install-option'
          ),
          array(
            'key' => 'machine',
            'value' => $_GET['machine']
          ),
        ),
        'include'=>$_GET['install_options'],
        'orderby'=>'meta_value_num',
        'meta_key'=>'index',
        'order'=>'ASC'
      ));
    }

    foreach($installOptions as $installOption) {
      $installOption->meta = get_post_meta($installOption->ID);
      // check for alternative post_content
      if (isset($installOption->meta['text'][0])) {
        $text_post = get_post($installOption->meta['text'][0]);
        $installOption->post_content = $text_post->post_content;
      }
      $installOption->post_content_html = nl2br($installOption->post_content);
      $installOptionTotal += $installOption->meta['price'][0];
    }
    
    $totalPrice += $installOptionTotal;
    $tax = 0.21 * $totalPrice;
    $totalInclPrice = $totalPrice + $tax;

    $html = '';
    ob_start();
    $plugindir = dirname( __FILE__ );

    // pdf main
    $override = locate_template('content-coolcalc-pdf.php', false) == '';
    if ($override) {
      include($plugindir . '/templates/content-coolcalc-pdf.php');
    }else{
      get_template_part('content','coolcalc-pdf');
    }

    $html = ob_get_contents();
    ob_end_clean();

    // pdf header
    $header = '';
    ob_start();
    $override = locate_template('content-coolcalc-pdf-header.php', false) == '';
    if ($override) {
      include($plugindir . '/templates/content-coolcalc-pdf-header.php');
    }else{
      get_template_part('content','coolcalc-pdf-header');
    }

    $header = ob_get_contents();
    ob_end_clean();

    // pdf footer
    $footer = '';
    ob_start();
    $override = locate_template('content-coolcalc-pdf-footer.php', false) == '';
    if ($override) {
      include($plugindir . '/templates/content-coolcalc-pdf-footer.php');
    }else{
      get_template_part('content','coolcalc-pdf-footer');
    }

    $footer = ob_get_contents();
    ob_end_clean();

    // pdf css
    $stylesheet = '';
    ob_start();
    $override = locate_template('content-coolcalc-pdf-stylesheet.php', false) == '';
    if ($override) {
      include($plugindir . '/templates/content-coolcalc-pdf-stylesheet.php');
    }else{
      get_template_part('content','coolcalc-pdf-stylesheet');
    }

    $stylesheet = ob_get_contents();
    ob_end_clean();

    include("includes/mpdf/mpdf.php");

    // to settings?
    $mpdf=new mPDF('','', 0, 'OpenSans', 0, 0, 30, 30, 0, 0);

    $mpdf->SetHTMLHeader($header);
    $mpdf->SetHTMLFooter($footer);
    $mpdf->setAutoTopMargin = '10mm';
    // LOAD a stylesheet
    $mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($html);
    date_default_timezone_set('Europe/Amsterdam');
    $strDate = strftime('%Y%m%d-%H%M');
    $mpdf->Output('Offerte-goedkopekoelmachines-'.$strDate.'.pdf','I');
    exit;
  }
  /** END OF AJAX **/

  /**
   * Shortcodes
   **/
  public function shortcode_model_list($atts) {

    $machines = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product',
      'meta_query'=> array(
        array(
          'key'=>'producttype',
          'value'=>'machine'
        )
      ),
      'orderby'=>'meta_value_num',
      'meta_key'=>'capacity',
      'order'=>'ASC'
    ));

    foreach($machines as $machine) {
      $machine->meta = get_post_meta($machine->ID);
    }

    $calculatorPageID = 11;

    $override = locate_template('content-coolcalc-model-list.php', false) == '';
    if ($override) {
      ob_start();
      $plugindir = dirname( __FILE__ );
      include($plugindir . '/templates/content-coolcalc-model-list.php');
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;
    }

    ob_start();
    include(locate_template('content-coolcalc-model-list.php'));
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;

  }

  public function shortcode_calculator($atts) {

    $machines = get_posts(array(
      'posts_per_page' => -1,
      'post_type'=>'cc_product',
      'meta_key'=>'producttype',
      'meta_value'=>'machine',
      'order_by'=>'title'
    ));

    foreach($machines as $machine) {
      $machine->meta = get_post_meta($machine->ID);
    }

    // calculator page
    $calculatorPageID = 11;

    $override = locate_template('content-coolcalc-calculator.php', false) == '';
    if ($override) {
      ob_start();
      $plugindir = dirname( __FILE__ );
      include($plugindir . '/templates/content-coolcalc-calculator.php');
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;
    }

    ob_start();
    get_template_part('content','coolcalc-calculator');
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;

  }
}
