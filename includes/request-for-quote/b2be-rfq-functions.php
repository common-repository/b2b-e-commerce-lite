<?php
/**
 * Functions used by plugins
 *
 * @since 2.5.0
 * @package woocomerce/templates
 */

/**
 * Get all rfq statuses.
 *
 * @since 1.1.1.0
 * @return array
 */
function b2be_get_quotes_statuses() {
	$quote_statuses = array(
		'requested'     => _x( 'Requested', 'Quote status', 'b2b-ecommerce' ),
		'quoted'        => _x( 'Quoted', 'Quote status', 'b2b-ecommerce' ),
		'accepted'      => _x( 'Accepted', 'Quote status', 'b2b-ecommerce' ),
		'need-revision' => _x( 'Need Revision', 'Quote status', 'b2b-ecommerce' ),
		'rejected'      => _x( 'Rejected', 'Quote status', 'b2b-ecommerce' ),
	);
	return apply_filters( 'b2be_rfq_quote_statuses', $quote_statuses );
}

/**
 * Gets the url to remove an item from the cart.
 *
 * @since 3.3.0
 * @param string $cart_item_key contains the id of the cart item.
 * @return string url to page
 */
function b2be_get_cart_remove_url( $cart_item_key ) {
	$cart_page_url = wc_get_page_permalink( B2BE_RFQ_CART_SLUG );
	return apply_filters( 'b2be_get_remove_url', $cart_page_url ? wp_nonce_url( add_query_arg( 'remove_rfq_item', $cart_item_key, $cart_page_url ), 'woocommerce-cart' ) : '' );
}

/**
 * Add to cart messages.
 *
 * @param int|array $products Product ID list or single product ID.
 * @param bool      $show_qty Should qty's be shown? Added in 2.6.0.
 * @param bool      $return   Return message rather than add it.
 *
 * @return mixed
 */
function b2be_add_to_rfq_message( $products, $show_qty = false, $return = false ) {
	$titles = array();
	$count  = 0;

	if ( ! is_array( $products ) ) {
		$products = array( $products => 1 );
		$show_qty = false;
	}

	if ( ! $show_qty ) {
		$products = array_fill_keys( array_keys( $products ), 1 );
	}

	foreach ( $products as $product_id => $qty ) {
		/* translators: %s: product name */
		$titles[] = apply_filters( 'woocommerce_add_to_cart_qty_html', ( $qty > 1 ? absint( $qty ) . ' &times; ' : '' ), $product_id ) . apply_filters( 'b2be_add_to_cart_item_name_in_quotes', sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'b2b-ecommerce' ), strip_tags( get_the_title( $product_id ) ) ), $product_id );
		$count   += $qty;
	}

	$titles = array_filter( $titles );
	/* translators: %s: product name */
	$added_text = sprintf( _n( '%s has been added to your RFQ.', '%s have been added to your RFQ.', $count, 'b2b-ecommerce' ), wc_format_list_of_items( $titles ) );

	// Output success messages.
	if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
		$return_to = apply_filters( 'b2be_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );
		$message   = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s', esc_url( $return_to ), esc_html__( 'Continue shopping', 'b2b-ecommerce' ), esc_html( $added_text ) );
	} else {
		$message = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s', esc_url( b2be_get_rfq_cart_url() ), esc_html__( 'View RFQ', 'b2b-ecommerce' ), esc_html( $added_text ) );
	}

	if ( has_filter( 'wc_add_to_cart_message' ) ) {
		wc_deprecated_function( 'The wc_add_to_cart_message filter', '3.0', 'wc_add_to_cart_message_html' );
		$message = apply_filters( 'wc_add_to_cart_message', $message, $product_id );
	}

	$message = apply_filters( 'wc_add_to_cart_message_html', $message, $products, $show_qty );

	if ( $return ) {
		return $message;
	} else {
		wc_add_notice( $message, apply_filters( 'b2be_add_to_rfq_notice_type', 'success' ) );
	}
}
/**
 * Get Quote Status Name.
 *
 * @param string $status variable.
 * @return string
 */
function b2be_get_quote_status_name( $status ) {
	$statuses = b2be_get_quotes_statuses();
	$status   = isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
	return $status;
}
/**
 * Get Quote.
 *
 * @param bool $quote_id variable.
 * @return bool
 */
function b2be_get_quote( $quote_id = false ) {
	$quote_id = b2be_get_quote_id( $quote_id );
	if ( ! $quote_id ) {
		return false;
	}

	try {
		return new B2BE_RFQ_Quote( $quote_id );
	} catch ( Exception $e ) {
		wc_caught_exception( $e );
		return false;
	}
}
/**
 * Get Quote Id.
 *
 * @param string $quote variable.
 * @return string
 */
function b2be_get_quote_id( $quote ) {
	global $post;

	if ( false === $quote && is_a( $post, 'WP_Post' ) && 'quote' === get_post_type( $post ) ) {
		return absint( $post->ID );
	} elseif ( is_numeric( $quote ) ) {
		return $quote;
	} elseif ( $quote instanceof B2BE_RFQ_Quote ) {
		return $quote->get_id();
	} elseif ( ! empty( $quote->ID ) ) {
		return $quote->ID;
	} else {
		return false;
	}
}
/**
 * Get Quote Actions
 *
 * @param string $quote variable.
 * @return bool
 */
function b2be_get_account_quotes_actions( $quote ) {
	if ( ! is_object( $quote ) ) {
		$quote_id = absint( $quote );
		$quote    = b2be_get_quote( $quote_id );
	}

	$add_to_rfq_btn_txt = get_option( 'b2be_rfq_add_to_rfq_button_text' );
	$accept_btn_txt     = get_option( 'b2be_rfq_accept_rfq_button_text' );
	$revision_btn_txt   = get_option( 'b2be_rfq_revison_rfq_button_text' );
	$view_btn_txt       = get_option( 'b2be_rfq_view_rfq_button_text' );
	$reject_btn_txt     = get_option( 'b2be_rfq_reject_rfq_button_text' );

	$actions = array(
		'accept'               => array(
			'url'  => wp_nonce_url( add_query_arg( 'accept_quote', $quote->get_id() ), 'b2be-rfq-quote_action' ),
			'name' => ( '' !== $accept_btn_txt ) ? $accept_btn_txt : 'Accept',
		),
		'need-revision'        => array(
			'url'  => wp_nonce_url( add_query_arg( 'revise_quote', $quote->get_id() ), 'b2be-rfq-quote_action' ),
			'name' => ( '' !== $revision_btn_txt ) ? $revision_btn_txt : 'Need Revision',
		),
		'reject'               => array(
			'url'  => wp_nonce_url( add_query_arg( 'reject_quote', $quote->get_id() ), 'b2be-rfq-quote_action' ),
			'name' => ( '' !== $reject_btn_txt ) ? $reject_btn_txt : 'Reject',
		),
		'view'                 => array(
			'url'  => $quote->get_view_quote_url(),
			'name' => ( '' !== $view_btn_txt ) ? $view_btn_txt : 'View',
		),
		'check-out'            => array(
			'url'  => wp_nonce_url( add_query_arg( 'rfq_check_out', $quote->get_id() ), 'b2be-rfq-quote_action' ),
			'name' => __( 'Check Out', 'b2b-ecommerce' ),
		),
		'accept-and-check-out' => array(
			'url'  => wp_nonce_url( add_query_arg( 'accept_and_check_out', $quote->get_id() ), 'b2be-rfq-quote_action' ),
			'name' => __( 'Accept And Check Out', 'b2b-ecommerce' ),
		),

	);
	if ( ! in_array( $quote->get_status(), apply_filters( 'b2be_rfq_valid_order_statuses_for_accept', array( 'quoted' ), $quote ), true ) ) {
		unset( $actions['accept'], $actions['need-revision'], $actions['reject'], $actions['accept-and-check-out'] );

	}

	return apply_filters( 'b2be_my_account_my_quotes_actions', $actions, $quote );
}
