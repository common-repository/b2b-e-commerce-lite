<?php
/**
 * Functon File
 *
 * @since 2.5.0
 * @package Woocomerce
 */

if ( ! class_exists( 'B2BE_WC_Dependencies' ) ) {
	require_once 'class-wc-dependencies.php';
}

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'b2be_is_woocommerce_activated' ) ) {
	/**
	 *  Is Woocomerce Activated
	 *
	 * @return bool
	 */
	function b2be_is_woocommerce_activated() {
		if ( B2BE_WC_Dependencies::woocommerce_active_check() ) {
			return true;
		} else {
			add_action( 'admin_notices', 'b2be_woocommerce_inactive_notice' );
			return false;
		}
	}
}

/**
 * Get The Current user role name.
 * 
 * @return string
 */
function b2be_get_formated_userrole_name() {
	if ( is_user_logged_in() ) {
		global $wp_roles;
		$all_roles_names       = $wp_roles->get_names();
		$user_obj              = get_user_by( 'id', get_current_user_id() );
		$curennt_userrole_name = $all_roles_names[ array_values( $user_obj->roles )[0] ];
		return $curennt_userrole_name;
	}
	return false;

}

if ( ! function_exists( 'b2be_is_required_login' ) ) {

	/**
	 * Check For Required Login Condition.
	 *
	 * @param object $product current product.
	 */
	function b2be_is_required_login( $product ) {

		$required_for_all      = ! empty( get_option( 'codup_hide_for_all' ) ) ? get_option( 'codup_hide_for_all' ) : array( '' );
		if ( 'yes' == $required_for_all ) {
			return true;
		}
		return false;

	}
}

/**
 * Function to return RFQ cart page url.
 */
function b2be_get_rfq_cart_url() {

	$rfq_cart_url = ! empty( get_option( 'b2be_rfq_cart_page' ) ) ? get_page_link( get_option( 'b2be_rfq_cart_page' ) ) : site_url() . '/rfq';

	return apply_filters( 'rfq_cart_url', $rfq_cart_url );

}

/**
 * Admin notice if WooCommerce is inactive.
 */
function b2be_woocommerce_inactive_notice() {
	if ( current_user_can( 'activate_plugins' ) ) : ?>	
		<div id="message" class="error">
			<p>
			<?php
				$install_url = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => 'woocommerce',
						),
						admin_url( 'update.php' )
					),
					'install-plugin_woocommerce'
				);
				/* translators: %s: is activated */
				printf( esc_html__( '%1$sB2B Ecommerce For Woocommerce is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for B2B Ecommerce For Woocommerce to work. Please %5$sinstall & activate WooCommerce &raquo;%6$s', 'b2b-ecommerce' ), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . esc_url( $install_url ) . '">', '</a>' );
			?>
			</p>
		</div>
		<?php
	endif;
}

/**
 * Return payment methods.
 * 
 * @param string $format Format to get desired payments method.
 */
function b2be_get_formatted_payment_methods( $format = null ) {
	
	$gateways = array();
	$b2b_custom_gateways = get_option( 'b2be_payment_method' );
	
	foreach ( WC()->payment_gateways->payment_gateways as $_available_gateways ) {
		
		if ( 'woocommerce' === $format ) {
			if( in_array( $_available_gateways->title, $b2b_custom_gateways ) ) {
				continue;
			}
		}
		elseif ( 'b2be_ecommerce' === $format ) {
			if( ! in_array( $_available_gateways->title, $b2b_custom_gateways ) ) {
				continue;
			}
		}

		if ( $_available_gateways->is_available() ) {
			if ( ! is_add_payment_method_page() ) {
				$gateways[ $_available_gateways->id ] = $_available_gateways;
			} elseif ( $_available_gateways->supports( 'add_payment_method' ) || $_available_gateways->supports( 'tokenization' ) ) {
				$gateways[ $_available_gateways->id ] = $_available_gateways;
			}
		}
	}

	return $gateways;

}

/**
 * Return payment methods.
 * 
 * @param int $user_id User Id For Which Payments Methods Are To Be Returned.
 */
function b2be_get_payment_methods_by_user_id( $user_id ) {

	if( ! $user_id ) {
		return false;
	}

	$customer = get_user_by( 'ID', $user_id );
	$roles    = (array) $customer->roles;

	$wc_gateways = b2be_get_formatted_payment_methods( 'woocommerce' );
	$wc_gateways = b2be_get_formatted_payment_methods( 'woocommerce' );

	if ( ! $wc_gateways ) {
		return;
	}

	if ( ! empty( $roles[0] ) && 0 != b2be_custom_post_exists( $roles[0] ) ) {
		$post_id = b2be_custom_post_exists( $roles[0] );
	}

	$gateways = [];
	foreach ( $wc_gateways as $id => $_available_gateways ) {
		if ( 'yes' === get_user_meta( $user_id, $id, true ) ) {
			array_push( $gateways, $_available_gateways->title );
		}
		elseif ( 'yes' === get_post_meta( $post_id, $id, true ) ) {
			array_push( $gateways, $_available_gateways->title );
		}
	}
	
	$b2be_gateways = b2be_get_formatted_payment_methods( 'b2be_ecommerce' );
	$b2be_gateways_ids = array_keys( $b2be_gateways );
	
	// For B2B payment methods...
	if ( ! empty( get_user_meta( $user_id, 'b2be_user_based_payment_method', true ) ) && in_array( get_user_meta( $user_id, 'b2be_user_based_payment_method', true ), $b2be_gateways_ids ) ) { 
		array_push( $gateways, $b2be_gateways[ get_user_meta( $user_id, 'b2be_user_based_payment_method', true ) ]->title );
	}
	elseif ( ! empty( get_post_meta( $post_id, 'b2be_role_based_payment_method', true ) ) && in_array( get_post_meta( $post_id, 'b2be_role_based_payment_method', true ), $b2be_gateways_ids ) ) { 
		array_push( $gateways, $b2be_gateways[ get_post_meta( $post_id, 'b2be_role_based_payment_method', true ) ]->title );
	}
	return $gateways ? $gateways : []; 
}

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'b2be_register_default_settings_for_rfq' ) ) {

	/**
	 *  Register Default Settings For Rfq.
	 */
	function b2be_register_default_settings_for_rfq() {

		$add_to_rfq_btn_txt = get_option( 'b2be_rfq_add_to_rfq_button_text' );
		$accept_btn_txt     = get_option( 'b2be_rfq_accept_rfq_button_text' );
		$revision_btn_txt   = get_option( 'b2be_rfq_revison_rfq_button_text' );
		$view_btn_txt       = get_option( 'b2be_rfq_view_rfq_button_text' );
		$reject_btn_txt     = get_option( 'b2be_rfq_reject_rfq_button_text' );
		$signup_fields      = get_option( 'codup_ecommerce_signup_field' );

		if ( null == $signup_fields || 0 == count( $signup_fields ) ) {

			$signup_fields = array(
				'0' => array(
					'field_title' => __( 'User Name', 'b2b-ecommerce' ),
					'field_type'  => 'text',
					'is_visible'  => 1,
					'is_required' => 1,
					'field_id'    => 'username',
				),
				'1' => array(
					'field_title' => __( 'First Name', 'b2b-ecommerce' ),
					'field_type'  => 'text',
					'is_visible'  => 1,
					'is_required' => 1,
					'field_id'    => 'first_name',
				),
				'2' => array(
					'field_title' => __( 'Last Name', 'b2b-ecommerce' ),
					'field_type'  => 'text',
					'is_visible'  => 1,
					'is_required' => 1,
					'field_id'    => 'last_name',
				),
				'3' => array(
					'field_title' => __( 'Email', 'b2b-ecommerce' ),
					'field_type'  => 'email',
					'is_visible'  => 1,
					'is_required' => 1,
					'field_id'    => 'email',
				),
				'4' => array(
					'field_title' => __( 'Date Of Birth', 'b2b-ecommerce' ),
					'field_type'  => 'date',
					'is_visible'  => 1,
					'is_required' => 1,
					'field_id'    => 'date_of_birth',
				),
				'5' => array(
					'field_title' => __( 'Role', 'b2b-ecommerce' ),
					'field_type'  => 'text',
					'is_visible'  => 1,
					'is_required' => 1,
					'field_id'    => 'role',
				),
			);
			update_option( 'codup_ecommerce_signup_field', $signup_fields );
		}

		if ( empty( $add_to_rfq_btn_txt ) ) {

			update_option( 'b2be_rfq_add_to_rfq_button_text', 'Add To RFQ' );

		}
		if ( empty( $accept_btn_txt ) ) {

			update_option( 'b2be_rfq_accept_rfq_button_text', 'Accept' );

		}
		if ( empty( $revision_btn_txt ) ) {

			update_option( 'b2be_rfq_revison_rfq_button_text', 'Need Revision' );

		}
		if ( empty( $view_btn_txt ) ) {

			update_option( 'b2be_rfq_view_rfq_button_text', 'View Quote' );

		}
		if ( empty( $reject_btn_txt ) ) {

			update_option( 'b2be_rfq_reject_rfq_button_text', 'Reject' );

		}

		if ( empty( get_option( 'b2be_rfq_enable_rfq' ) ) ) {
			update_option( 'b2be_rfq_enable_rfq', 'yes' );
		}
		if ( empty( get_option( 'b2be_rfq_disable_add_to_cart' ) ) ) {
			update_option( 'b2be_rfq_disable_add_to_cart', 'no' );
		}

		$args        = array(
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'fields'         => 'ids',
		);
		$posts_array = get_posts( $args );
		foreach ( $posts_array as $post_array ) {

			if ( empty( get_post_meta( $post_array, 'enable_rfq', true ) ) ) {
				update_post_meta( $post_array, 'enable_rfq', 'yes' );
			}

			if ( empty( get_post_meta( $post_array, 'disable_add_to_cart', true ) ) ) {
				update_post_meta( $post_array, 'disable_add_to_cart', 'no' );
			}
		}

		$cat_args     = array(
			'posts_per_page' => -1,
			'taxonomy'       => 'product_cat',
			'fields'         => 'ids',
			'hide_empty'     => false,
		);
		$category_ids = get_terms( $cat_args );

		foreach ( $category_ids as $cat_id ) {
			if ( empty( get_term_meta( $cat_id, 'taxonomy_setting' ) ) ) {
				delete_term_meta( $cat_id, 'taxonomy_setting' );
			}
		}

		$codup_rfq               = array();
		$codup_rfq['enable_rfq'] = 1;
		foreach ( $category_ids as $cat_id ) {
			if ( empty( get_term_meta( $cat_id, 'taxonomy_setting' ) ) ) {
				update_term_meta( $cat_id, 'taxonomy_setting', $codup_rfq );
			}
		}

		foreach ( wp_roles()->role_names as $post_name => $post_title ) {
			if ( 0 === b2be_custom_post_exists( $post_name ) ) {
				$post_arr = array(
					'post_title'     => $post_title,
					'post_name'      => $post_name,
					'post_status'    => 'publish',
					'comment_status' => 'closed',
					'post_type'      => 'codup-custom-roles',
				);
				$post_id  = wp_insert_post( $post_arr );

				if ( empty( get_post_meta( $post_id, 'enable_rfq', true ) ) ) {
					update_post_meta( $post_id, 'enable_rfq', 'yes' );
				}
				if ( empty( get_post_meta( $post_id, 'disable_add_to_cart', true ) ) ) {
					update_post_meta( $post_id, 'disable_add_to_cart', 'no' );
				}
			}
		}
	}
}
if ( ! function_exists( 'last_array_key' ) ) {

	/**
	 * Function to return last key of array.
	 *
	 * @param array $array Can be any array.
	 */
	function last_array_key( $array ) {
		if ( ! is_array( $array ) || empty( $array ) ) {
			return null;
		}

		return array_keys( $array )[ count( $array ) - 1 ];
	}
}
/**
 * Function to add requested quote count on Quote menu.
 */
function b2be_get_requested_quote_count() {

	$args   = array(
		'post_type'   => 'quote',
		'post_status' => 'requested',
		'numberposts' => -1,
	);
	$quotes = get_posts( $args );

	$count = 0;
	foreach ( $quotes as $key => $quote ) {
		if ( 'requested' == $quote->post_status ) {
			$count++;
		}
	}

	return $count;
}
