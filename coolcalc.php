<?php
/**
 * CoolCalc
 *
 * Calculator for quotes of cooling machines
 *
 * @package   coolcalc
 * @author    Sander Baas <sander@implode.nl>
 * @license   GPL-2.0+
 * @link      https://implode.nl
 * @copyright 2015 Sander Baas
 *
 * @wordpress-plugin
 * Plugin Name:       CoolCalc
 * Plugin URI:        https://implode.nl/coolcalc
 * Description:       Calculator for quotes of cooling machines
 * Version:           1.0.0
 * Author:            Sander Baas
 * Author URI:        http://implode.nl
 * Text Domain:       coolcalc-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/sanderbaas/coolcalc
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-plugin-name.php` with the name of the plugin's class file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-coolcalc.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook( __FILE__, array( 'CoolCalc', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CoolCalc', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
add_action( 'plugins_loaded', array( 'CoolCalc', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-plugin-name-admin.php` with the name of the plugin's admin file
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

  require_once( plugin_dir_path( __FILE__ ) . 'admin/class-coolcalc-admin.php' );
  add_action( 'plugins_loaded', array( 'CoolCalc_Admin', 'get_instance' ) );
}

/*----------------------------------------------------------------------------*
 * Widgets
 *----------------------------------------------------------------------------*/

  //require_once( plugin_dir_path( __FILE__ ) . 'quote-widget/class-widget.php' );
 
  //add_action( 'widgets_init', create_function( '', 'register_widget("CoolCalc_Quote_Widget");' ) );
