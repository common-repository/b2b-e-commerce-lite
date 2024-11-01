<?php
/**
 * Plugin Name: B2B E-commerce Lite
 * Description: The ultimate wholesale extension for WooCommerce stores.
 * Author: Codup
 * Author URI: http://codup.co
 * Version: 1.0.0.3
 * Domain Path: /languages
 * Text Domain: b2b-ecommerce-lite
 * WC requires at least: 3.8.0
 * WC tested up to: 5.1.0
 *
 * @package b2b-ecommerce-for-woocommerce-lite
 */

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'B2BE_PLUGIN_DIR', __DIR__ );
define( 'B2BE_PLUGIN_NAME', 'B2B E-commerce For WooCommerce Lite' );
define( 'B2BE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'B2BE_TEMPLATE_DIR', B2BE_PLUGIN_DIR . '/templates/request-for-quote/' );
define( 'CWSFG_TEMPLATE_DIR', B2BE_PLUGIN_DIR . '/templates/signup-form/' );
define( 'B2BE_ASSETS_DIR_URL', B2BE_PLUGIN_DIR_URL . 'assets/' );


require_once B2BE_PLUGIN_DIR . '/includes/functions.php';
require_once B2BE_PLUGIN_DIR . '/constants.php';
require_once B2BE_PLUGIN_DIR . '/includes/class-b2b-ecommerce-for-woocommerce.php';
require_once B2BE_PLUGIN_DIR . '/contact_us/class-b2be-contact-us.php';

/**
 * Check if WooCommerce is activated
 */
if ( b2be_is_woocommerce_activated() ) {
	new B2B_Ecommerce_For_WooCommerce_Lite();
	register_activation_hook( __FILE__, 'b2be_register_default_settings_for_rfq' );
}
