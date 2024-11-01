<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_Sign_Up_Form_Loader' ) ) {
	/**
	 * Class Codup_Role_Based_Discounts.
	 */
	class B2BE_Sign_Up_Form_Loader {
		/**
		 * Cart Variable.
		 */
		public function __construct() {

			$this->includes();
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_sign_up_form_backend_scripts' ) );
		}

		/**
		 *  Function Includes.
		 */
		public function includes() {

			include_once 'class-b2be-sign-up-form-settings.php';
			include_once 'class-b2be-sign-up-form.php';

		}

		/**
		 * Enqueue backend script.
		 *
		 * @param int $page_id page id.
		 */
		public function enqueue_sign_up_form_backend_scripts( $page_id ) {

			wp_enqueue_script( 'sign-up-form', B2BE_ASSETS_DIR_URL . '/js/signup-form/admin/sign-up-form.js', array( 'jquery' ), true );
			wp_localize_script(
				'sign-up-form',
				'sign_up_settings',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
			wp_enqueue_style( 'sign_up_css', B2BE_ASSETS_DIR_URL . 'css/sign-up-form/signup-form-settings.css', '', true );
		}

	}

}
new B2BE_Sign_Up_Form_Loader();
