<?php
/**
 * Shipping exempts Fields
 *
 * @package b2be-user-role-shipping-exempts.php
 */

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="b2be-custom-role-options" class="panel woocommerce_options_panel b2be-custom-role-options" style="display: block;">
	<div class="options_group" >
		<div class="form-field shipping-exempt-field">
			<div class="title">
				<?php echo esc_html__( 'Exempt Shipping Rate', 'b2b-ecommerce' ); ?>
			</div>
			<div class="desc-options">
				<input type="checkbox" class="checkbox" name="shipping_exempt" id="shipping_exempt" <?php echo ( 'on' == get_post_meta( $post_id, 'shipping_exempt', true ) ) ? 'checked' : ''; ?> >
				<span class="description"><?php echo esc_html__( 'Exempt Shipping Rate', 'b2b-ecommerce' ); ?></span>
			</div>
		</div>
	</div>
</div>
