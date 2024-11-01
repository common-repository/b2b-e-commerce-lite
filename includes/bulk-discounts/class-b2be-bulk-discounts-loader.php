<?php
/**
 * Bulk Discount.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_Bulk_Discounts_Loader' ) ) {
	/**
	 * Class B2BE_Bulk_Discounts.
	 */
	class B2BE_Bulk_Discounts_Loader {
		/**
		 * Constructor.
		 */
		public function __construct() {

			$this->includes();
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_bulk_discount_backend_scripts' ) );
		}

		/**
		 *  Function Includes.
		 */
		public function includes() {

			include_once 'class-b2be-bulk-discounts-settings.php';
			
		}

		/**
		 * Enqueue backend script.
		 *
		 * @param int $page_id Current Page Id.
		 */
		public function enqueue_bulk_discount_backend_scripts( $page_id ) {
			if ( 'woocommerce_page_wc-settings' == $page_id ) {
				if ( isset( $_GET['section'] ) && ( 'codup-bulk-discounts' == $_GET['section'] || 'codup-moq' == $_GET['section'] ) ) {

					wp_enqueue_style( 'bulk_discounts_settings_style', B2BE_ASSETS_DIR_URL . '/css/bulk-discount/bulk-discounts-settings.css', '', rand() );

				}
			}

		}

	}

}
new B2BE_Bulk_Discounts_Loader();
