<?php
/**
 * WC Ecommerce For Woocommerce Main Class.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

defined( 'ABSPATH' ) || exit;

/**
 * Codup_B2B_Ecommerce_For_Woocommerce class.
 */
class B2BE_MOQ_Settings {

	/**
	 * Return RFQ setting fields.
	 */
	public static function get_settings() {

		include B2BE_PLUGIN_DIR . '/includes/admin/moq/views/moq-fields.php';

	}

}
