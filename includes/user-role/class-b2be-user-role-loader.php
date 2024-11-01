<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_User_Role_Loader' ) ) {
	/**
	 * Class B2BE_User_Role.
	 */
	class B2BE_User_Role_Loader {
		/**
		 * Cart Variable.
		 */
		public function __construct() {
			$this->includes();
		}

		/**
		 *  Function Includes.
		 */
		public function includes() {
			include_once 'class-b2be-user-role.php';
			include_once 'b2be-user-role-function.php';
			include_once 'class-b2be-custom-roles-cpt.php';
		}

		/**
		 * Enqueue backend script.
		 */
		public function enqueue_user_role_frontend_scripts() {

			global $post;

			$variations    = '';
			$regular_price = '';
			$product       = wc_get_product( $post->ID );

			$is_enable_functionality = get_option( 'codup-role-based_enable_user_role' );

			if ( ! empty( $product ) && 'yes' == $is_enable_functionality ) {
				$variations    = b2be_get_discounted_variation_price( $product, '' );
				$regular_price = b2be_get_variation_regular_price( $product );
			}

			wp_enqueue_script( 'user_role_discount_script', B2BE_ASSETS_DIR_URL . '/js/role-based-discounts/user-role-discount.js', '', array( 'jquery' ), true );
			wp_localize_script(
				'user_role_discount_script',
				'user_role_based_discount',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'variation'     => ! empty( $variations ) ? $variations : array(),
					'regular_price' => ! empty( $regular_price ) ? $regular_price : '',
					'price_symbol'  => get_woocommerce_currency_symbol(),
				)
			);

		}

	}

}
new B2BE_User_Role_Loader();
