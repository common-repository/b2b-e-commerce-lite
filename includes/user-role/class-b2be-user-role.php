<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_User_Role' ) ) {
	/**
	 * Class B2BE_User_Role.
	 */
	class B2BE_User_Role {

		/**
		 * Construct.
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'exemption_init' ), 10, 1 );

		}

		/**
		 * Function to render exemption hooks.
		 */
		public function exemption_init() {

			add_filter( 'woocommerce_product_get_tax_class', array( $this, 'b2be_exempt_tax_class' ), 1, 2 );
			add_filter( 'woocommerce_product_variation_get_tax_class', array( $this, 'b2be_exempt_tax_class' ), 1, 2 );
			add_filter( 'woocommerce_product_needs_shipping', array( $this, 'disable_shipping_in_cart_page' ), 10, 2 );

		}

		/**
		 * Function to exempt shipping for user role.
		 *
		 * @param bool   $needs_shipping Need shipping or not?.
		 * @param object $product Product Object.
		 */
		public function disable_shipping_in_cart_page( $needs_shipping, $product ) {

			if ( ! is_user_logged_in() ) {
				return $needs_shipping;
			}

			$user  = wp_get_current_user();
			$roles = (array) $user->roles;
			if ( ! empty( $roles[0] ) && 0 != b2be_custom_post_exists( $roles[0] ) ) {
				$post_id            = b2be_custom_post_exists( $roles[0] );
				$is_exempt_shipping = get_post_meta( $post_id, 'shipping_exempt', true );
				if ( 'on' == $is_exempt_shipping ) {
					$needs_shipping = false;
				}
			}
			return $needs_shipping;
		}

		/**
		 * Function to exempt tax for user role.
		 *
		 * @param array  $tax_class Tax Classes.
		 * @param object $product Product object.
		 */
		public function b2be_exempt_tax_class( $tax_class, $product ) {

			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return $tax_class;
			}

			if ( ! is_user_logged_in() ) {
				return $tax_class;
			}

			$user  = wp_get_current_user();
			$roles = (array) $user->roles;
			if ( ! empty( $roles[0] ) && 0 != b2be_custom_post_exists( $roles[0] ) ) {
				$post_id = b2be_custom_post_exists( $roles[0] );

				$exempted_tax_classes = get_post_meta( $post_id, 'tax_exempt', true );

				if ( ! empty( $exempted_tax_classes ) && in_array( $tax_class, array_keys( $exempted_tax_classes ) ) ) {
					return 'none';
				} elseif ( ! empty( $exempted_tax_classes ) && in_array( 'standard', array_keys( $exempted_tax_classes ) ) && empty( $tax_class ) ) {
					return 'none';
				} else {
					return $tax_class;
				}
			}
		}

	}
}
new B2BE_User_Role();
