<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_Required_Login_Loader' ) ) {
	/**
	 * Class Codup_Role_Based_Discounts.
	 */
	class B2BE_Required_Login_Loader {
		/**
		 * Cart Variable.
		 */
		public function __construct() {

			$this->includes();
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_required_login_backend_scripts' ) );
		}

		/**
		 *  Function Includes.
		 */
		public function includes() {
			include_once 'class-b2be-required-login-settings.php';
			include_once 'class-b2be-required-login.php';
		}

		/**
		 * Enqueue backend script.
		 *
		 * @param int $page_id Current Page Id.
		 */
		public function enqueue_required_login_backend_scripts( $page_id ) {

			wp_enqueue_script( 'required_login', B2BE_ASSETS_DIR_URL . '/js/required-login-for-catalogue/admin/required-login-settings-multiselect.js', array( 'jquery' ), true );
			wp_localize_script(
				'required_login',
				'required_login_settings',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
			wp_enqueue_style( 'required-login-select2-styling', B2BE_ASSETS_DIR_URL . '/css/select2.min.css', null, 1 );
			wp_enqueue_script( 'required-login-select2-script', B2BE_ASSETS_DIR_URL . '/js/select2.min.js', array( 'jquery' ), true );

			wp_enqueue_style( 'required_login_css', B2BE_ASSETS_DIR_URL . '/css/required-login-for-catalogue/admin/required-login-for-catalogue.css', '', true );

		}

	}

}
new B2BE_Required_Login_Loader();
