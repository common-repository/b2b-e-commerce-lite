<?php
/**
 * Class B2BE_RFQ_Loader file.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Gett All the loader files here...

if ( ! class_exists( 'B2B_Ecommerce_For_WooCommerce_Lite' ) ) {

	/**
	 *  Class B2B_Ecommerce_For_WooCommerce_Lite.
	 */
	class B2B_Ecommerce_For_WooCommerce_Lite {

		/**
		 *  Constructor.
		 */
		public function __construct() {

			$this->include_b2b_ecommerce_main_files();
			B2B_Ecommerce_Section::init();
			add_action( 'plugins_loaded', array( $this, 'b2be_load_plugin_textdomain' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_b2b_scripts' ) );
		}

		/**
		 * Include style and scripts in all over pluign.
		 */
		public function enqueue_b2b_scripts() {
			wp_enqueue_style( 'b2b_scripts_style', B2BE_ASSETS_DIR_URL . '/css/style.css', array(), rand() );
		}

		/**
		 * Include all the main files of this plugin.
		 */
		public function include_b2b_ecommerce_main_files() {

			include_once 'class-b2b-ecommerce-section.php';
			require_once 'request-for-quote/class-b2be-rfq-loader.php';
			require_once 'signup-form/class-b2be-sign-up-form-loader.php';
			require_once 'payment-method/class-b2be-payment-method-loader.php';
			require_once 'required-login-for-catalogue/class-b2be-required-login-loader.php';
			require_once 'bulk-discounts/class-b2be-bulk-discounts-loader.php';
			require_once 'moq/class-b2be-moq-loader.php';
			require_once 'user-role/class-b2be-user-role-loader.php';
			require_once 're-order/class-b2be-reorder-loader.php';
			
		}

		/**
		 * Languages loaded.
		 */
		public function b2be_load_plugin_textdomain() {
			load_plugin_textdomain( 'b2b-ecommerce-lite', false, basename( B2BE_BASENAME ) . '/languages/' );
		}

	}
}
