<?php
/**
 * Quotes
 *
 * Shows quotes on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/quotes.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 */

defined( 'ABSPATH' ) || exit;

$has_quotes = false;
do_action( 'b2be_rfq_before_account_quotes', $has_quotes );
$columns = array(
	'quote-number'  => __( 'Quote # ', 'b2b-ecommerce' ),
	'quote-date'    => __( 'Date', 'b2b-ecommerce' ),
	'quote-status'  => __( 'Status', 'b2b-ecommerce' ),
	'quote-total'   => __( 'Quoted Total', 'b2b-ecommerce' ),
	'quote-actions' => __( 'Actions', 'b2b-ecommerce' ),
);

$customer_quotes = get_posts(
	apply_filters(
		'woocommerce_my_account_my_quotes_query',
		array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => 'quote',
			'post_status' => array_keys( b2be_get_quotes_statuses() ),
		)
	)
);
?>

<?php if ( $customer_quotes ) { ?>
	<table id="view_rfq_order" class="woocommerce-quotes-table woocommerce-MyAccount-quotes shop_table shop_table_responsive my_account_quotes account-quotes-table">
		<thead>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<th class="woocommerce-quotes-table__header woocommerce-quotes-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $customer_quotes as $customer_quote ) {

				$quote      = b2be_get_quote( $customer_quote ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
				$item_count = $quote->get_item_count();
				?>
				<tr class="woocommerce-quotes-table__row woocommerce-quotes-table__row--status-<?php echo esc_attr( $status ); ?> quote">
					<?php foreach ( $columns as $column_id => $column_name ) : ?>
						<td class="woocommerce-quotes-table__cell woocommerce-quotes-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_quotes_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_quotes_column_' . $column_id, $order ); ?>

							<?php elseif ( 'quote-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $quote->get_view_quote_url() ); ?>">
									<?php echo esc_html( _x( '#', 'hash before order number', 'b2b-ecommerce' ) . $customer_quote->ID ); ?>
								</a>

								<?php
							elseif ( 'quote-date' === $column_id ) :
								$date = date_create( $customer_quote->post_date );
								echo wp_kses_post( date_format( $date, 'F d, Y' ) );
								?>
													  
							<?php elseif ( 'quote-status' === $column_id ) : ?>
								<?php
								echo esc_html( b2be_get_quote_status_name( $customer_quote->post_status ) );
								?>

							<?php elseif ( 'quote-total' === $column_id ) : ?>
								<?php
									$total = 0;
									$items = get_post_meta( $customer_quote->ID, 'items', true );
								if ( 'Requested' !== b2be_get_quote_status_name( $customer_quote->post_status ) ) {

									if ( $items ) {
										foreach ( $items as $item ) {
											$total += $item['total'];
										}
									}
								} else {
									if ( $items ) {
										foreach ( $items as $item ) {
											$total += $item['subtotal'];
										}
									}
								}
								/* translators: 1: formatted order total 2: total order items */
								echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'b2b-ecommerce' ), wc_price( $total ), $item_count ) );
								$total = 0;
								?>

							<?php elseif ( 'quote-actions' === $column_id ) : ?>
								<?php
								$rfq_actions = b2be_get_account_quotes_actions( $quote );

								if ( ! empty( $rfq_actions ) ) {
									foreach ( $rfq_actions as $key => $rfq_action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited                                       

										if ( 'check-out' === $key ) {
											if ( 'Accepted' === b2be_get_quote_status_name( $customer_quote->post_status ) ) {
												echo '<a style="width: max-content;" href="' . esc_url( $rfq_action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '" onclick="return confirm(\'Are you sure? all items of the quote will be added to your cart.\')">' . esc_html( $rfq_action['name'] ) . '</a>';
											}
										} elseif ( 'accept-and-check-out' === $key ) {
											echo '<a style="width: max-content;" href="' . esc_url( $rfq_action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '" onclick="return confirm(\'Are you sure? all items of the quote will be added to your cart.\')">' . esc_html( $rfq_action['name'] ) . '</a>';
										} else {
											echo '<a href="' . esc_url( $rfq_action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $rfq_action['name'] ) . '</a>';
										}
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php

	do_action( 'b2be_rfq_before_account_quotes_pagination' );
	?>
<?php } else { ?>
	
	<div style="background: antiquewhite;padding: 10px;text-align: center;">
		<p style="margin:0px">
			<span><?php echo 'You Have Not Submitted Any Quotes'; ?></span>
		</p>
	</div>
	
<?php } ?>

<?php do_action( 'b2be_rfq_after_account_quotes', $has_quotes ); ?>
