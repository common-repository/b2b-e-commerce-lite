<?php
/**
 * Template for the tier entry. Based on the WooCommerce file class-wc-admin-settings.php.
 *
 * @global string CWL_SLUG
 *
 * @var $this CodupWooLoyaltyTiersFields
 * @var $field_config string[]
 * @package B2B_E-commerce_For_WooCommerce/templates
 */

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style( 'b2be-rfq-product-category-css' )
?>

<tr valign="top" class="sign-up-form-footer">
	<th class="input-column" colspan="5">
		<a href="javascript: void(0);" class="sfg-add-field-button">
			<?php echo esc_html__( 'Add Another Field', 'b2b-ecommerce' ); ?>
		</a>
	</th>
</tr>

<div class="rfq-sign-up-form-disabled-overlay-text">
	<span>
		<?php esc_html_e( 'Purchase ', 'b2b-ecommerce' ); ?>
		<a href="https://woocommerce.com/products/b2b-ecommerce-for-woocommerce/"><?php esc_html_e( 'B2B Ecommerce For WooCommerce Pro', 'b2b-ecommerce' ); ?></a>
		<?php esc_html_e( ' To Get The Disabled Features.', 'b2b-ecommerce' ); ?>
	</span>
</div>

<?php
