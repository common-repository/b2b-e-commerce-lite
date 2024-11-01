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
class B2BE_Required_Login_Settings {

	/**
	 * Return required login setting fields.
	 *
	 * @return type
	 */
	public static function get_settings() {

		$settings = self::get_required_login_fields();

		return $settings;
	}

	/**
	 * Return required login setting fields.
	 *
	 * @return type
	 */
	public static function get_required_login_fields() {

		$category_query = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
			)
		);

		$product_query = get_posts(
			array(
				'post_type'   => 'product',
				'numberposts' => -1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			)
		);

		$pages_query = get_pages();

		foreach ( $product_query as $product_id ) {

			$product                     = wc_get_product( $product_id );
			$all_products[ $product_id ] = $product->get_name();

		}

		foreach ( $category_query as $key => $value ) {

			$product_categories[ $value->term_taxonomy_id ] = $value->name;

		}

		foreach ( $pages_query as $key => $page_object ) {

			if ( 'my-account' == $page_object->post_name ) {
				continue;
			}
			$all_pages[ $page_object->ID ] = $page_object->post_title;

		}

		$fields = array(
			'general_title'         => array(
				'title' => __( 'Price Visibility for Products', 'b2b-ecommerce' ),
				'type'  => 'title',
				'id'    => 'codup_required_login_title',
				'desc'  => __( 'This Allows You To Hide Catalogue Price For Non-Logged In Customer. <p class="b2be-required-login-disabled-overlay-text">Purchase <a href="https://woocommerce.com/products/b2b-ecommerce-for-woocommerce/">B2B Ecommerce For WooCommerce Pro</a> To Get The Disabled Features.</p>', 'b2b-ecommerce' ),
			),
			'enable_hide_catalogue' => array(
				'name'     => __( 'Enable Catalogue Price Visibility', 'b2b-ecommerce' ),
				'type'     => 'checkbox',
				'id'       => 'codup_enable_hide_catalogue',
				'desc'     => __( 'Enable Or Disable the functionality', 'b2b-ecommerce' ),
				'desc_tip' => __( 'Select it to hide price for no logged-in customers', 'b2b-ecommerce' ),
			),
			'hide_for_all'          => array(
				'name'     => __( 'Hide Whole Catalogue Price', 'b2b-ecommerce' ),
				'type'     => 'checkbox',
				'id'       => 'codup_hide_for_all',
				'desc_tip' => __( 'Hide whole catalogue price for non logged in users.', 'b2b-ecommerce' ),
				'desc'     => __( 'Replaces price with the "Sign In to View" button on all products if the customer is not logged in.', 'b2b-ecommerce' ),
			),
			'restrict_whole_store'  => array(
				'name'     => __( 'Restrict Whole Store', 'b2b-ecommerce' ),
				'type'     => 'checkbox',
				'id'       => 'codup_restrict_store',
				'desc_tip' => __( 'Restrict Whole Store for non logged in users.', 'b2b-ecommerce' ),
				'desc'     => __( 'This will not let the customer to access the store if the customer is not logged in.', 'b2b-ecommerce' ),
				'custom_attributes' => array(
					'disabled' => 'disabled'
				),
			),
			'hide_by_category'      => array(
				'name'     => __( 'Hide Catalogue Price By Category', 'b2b-ecommerce' ),
				'type'     => 'multiselect',
				'id'       => 'codup_hide_by_category',
				'class'    => 'codup_req_specific_settings',
				'options'  => $product_categories,
				'desc_tip' => __( 'Hide prices of products in selected categories if customer is not logged in.', 'b2b-ecommerce' ),
				'desc'     => __( 'Select categories of products you want to hide prices for.', 'b2b-ecommerce' ),
				'custom_attributes' => array(
					'disabled' => 'disabled'
				),
			),

			'hide_by_product'       => array(
				'name'     => __( 'Hide Catalogue Price By Product', 'b2b-ecommerce' ),
				'type'     => 'multiselect',
				'id'       => 'codup_hide_by_product',
				'class'    => 'codup_req_specific_settings',
				'options'  => $all_products,
				'desc_tip' => __( 'Hide prices of selected products if customer is not logged in.', 'b2b-ecommerce' ),
				'desc'     => __( 'Select products you want to hide prices for.', 'b2b-ecommerce' ),
				'custom_attributes' => array(
					'disabled' => 'disabled'
				),
			),

			'hide_by_pages'         => array(
				'name'     => __( 'Hide Pages', 'b2b-ecommerce' ),
				'type'     => 'multiselect',
				'id'       => 'codup_hide_by_pages',
				'class'    => '',
				'options'  => $all_pages,
				'desc_tip' => __( 'Hide selected pages if customer is not logged in.', 'b2b-ecommerce' ),
				'desc'     => __( 'Select the pages you want to hide.', 'b2b-ecommerce' ),
				'custom_attributes' => array(
					'disabled' => 'disabled'
				),
			),

		);

		$fields['general_title_end'] = array(
			'type' => 'sectionend',
			'id'   => 'codup_required_login_title',
		);
		return $fields;
	}

}
