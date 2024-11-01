<?php
/**
 * Bulk Discount Settings Class.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

defined( 'ABSPATH' ) || exit;

/**
 * Codup_B2B_Ecommerce_For_Woocommerce class.
 */
class B2BE_Bulk_Discounts_Settings {

	/**
	 * Return Discount Rules setting fields.
	 */
	public static function get_settings() {

		include B2BE_PLUGIN_DIR . '/includes/admin/bulk-discounts/views/bulk-discounts-fields.php';

	}

}
