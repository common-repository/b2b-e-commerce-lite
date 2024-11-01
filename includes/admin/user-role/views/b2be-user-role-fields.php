<?php
/**
 * User role fields exempts Fields
 *
 * @package b2be-user-role-fields.php
 */

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style( 'b2be-rfq-product-category-css' );
?>
<div id="b2be-custom-role-options" class="panel woocommerce_options_panel b2be-custom-role-options" style="display: block;">
	<div class="options_group">
		<div class="form-field enable_rfq_field">
			<div class="title">
			   <?php echo esc_html__( 'Enable RFQ', 'b2b-ecommerce' ); ?>
			</div>
			<div class="desc-options">
				<input type="checkbox" class="checkbox" name="enable_rfq" id="enable_rfq" <?php echo ( 'yes' == get_post_meta( $post_id, 'enable_rfq', true ) ) ? 'checked' : ''; ?>>
				<span class="description"><?php echo wp_kses_post( ( 'yes' == get_post_meta( $post_id, 'enable_rfq', true ) ) ? esc_html__( 'Adds a RFQ button for this role.', 'b2b-ecommerce' ) : esc_html__( 'Hide a RFQ button for this role.', 'b2b-ecommerce' ) ); ?></span>
			</div>
		</div>
		<div class="form-field disable_add_to_cart_field">
			<div class="title">
				<?php echo esc_html__( 'Disable Add to Cart', 'b2b-ecommerce' ); ?>
			</div>
			<div class="desc-options">
				<input type="checkbox" class="checkbox" name="disable_add_to_cart" id="disable_add_to_cart" <?php echo ( 'yes' == get_post_meta( $post_id, 'disable_add_to_cart', true ) ) ? 'checked' : ''; ?>>
				<span class="description"><?php echo wp_kses_post( ( 'yes' == get_post_meta( $post_id, 'disable_add_to_cart', true ) ) ? esc_html__( 'Hide Add to cart on this role.', 'b2b-ecommerce' ) : esc_html__( 'Show Add to cart on this role.', 'b2b-ecommerce' ) ); ?></span>
			</div>
		</div>				
	</div>
	<div class="rfq-role-disabled-overlay">
		<p class="rfq-role-disabled-overlay-text">
			<?php esc_html_e( 'Purchase ', 'b2b-ecommerce' ); ?>
			<a style="color: gold;" href="https://woocommerce.com/products/b2b-ecommerce-for-woocommerce/"><?php esc_html_e( 'B2B Ecommerce For WooCommerce Pro', 'b2b-ecommerce' ); ?></a>
			<?php esc_html_e( ' To Get This Feature.', 'b2b-ecommerce' ); ?>
		</p>
	</div>
</div>