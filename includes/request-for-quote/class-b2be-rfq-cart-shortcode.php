<?php
/**
 * WC RFQ Cart Shortcode.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode cart class.
 */
class B2BE_RFQ_Cart_ShortCode {

	/**
	 * Output the rfq cart shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts ) {

		if ( WC()->rfq->is_empty() ) {
			wc_get_template( 'rfq/cart-empty.php', array(), 'b2b-ecommerce-for-woocommerce', B2BE_TEMPLATE_DIR );
		} else {
			wc_get_template( 'rfq/cart.php', array(), 'b2b-ecommerce-for-woocommerce', B2BE_TEMPLATE_DIR );
		}

	}
}
