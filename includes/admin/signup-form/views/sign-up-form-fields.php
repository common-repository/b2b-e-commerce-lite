<?php
/**
 * Template for the tier entry. Based on the WooCommerce file class-wc-admin-settings.php.
 *
 * @var $this CodupWooLoyaltyTiersFields
 * @var $field_config string[]
 * @package B2B_E-commerce_For_WooCommerce/templates
 */

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr valign="top" >
	<th class="titledesc" style="text-align:center"><?php echo esc_html__( 'Field Names ', 'b2b-ecommerce' ); ?><?php echo wp_kses_post( wc_help_tip( __( 'Enter fields name', 'b2b-ecommerce' ) ) ); ?></th>
	<th class="titledesc role-discount-title" style="padding-left: 11px;text-align: center;"><?php echo esc_html__( 'Field Type ', 'b2b-ecommerce' ); ?><?php echo wp_kses_post( wc_help_tip( __( 'Select field type', 'b2b-ecommerce' ) ) ); ?></th>
	<th class="titledesc role-discount-title" style="padding-left: 11px;text-align: center;"><?php echo esc_html__( 'Required ', 'b2b-ecommerce' ); ?><?php echo wp_kses_post( wc_help_tip( __( 'This will make the field required', 'b2b-ecommerce' ) ) ); ?></th>
	<th class="titledesc role-discount-title" style="padding-left: 11px;text-align: center;"><?php echo esc_html__( 'Visible ', 'b2b-ecommerce' ); ?><?php echo wp_kses_post( wc_help_tip( __( 'This will make the field visible', 'b2b-ecommerce' ) ) ); ?></th>
</tr>
<?php

$default_fields = get_option( 'codup_ecommerce_signup_field' );
$index          = 0;
foreach ( $field_config['signup'] as $key => $value ) {
	?>
	<tr valign="top" class="cwl-tier-row <?php echo ( isset( $value['field_type'] ) && 'email' == $value['field_type'] ) ? 'email_field' : ''; ?>" data-signup-field-number="<?php echo esc_attr( $index ); ?>"  >
		
		<td class="forminp forminp-<?php echo wp_kses_post( sanitize_title( $field_config['type'] ) ); ?> input-column" style="padding:10px;">
			<input
				name="<?php echo esc_attr( $field_config['id'] ); ?>[<?php echo esc_attr( $index ); ?>][field_title]"
				type="text"
				dir="ltr"
				style="<?php echo esc_attr( $field_config['css'] ); ?>;width:100%"
				class="<?php echo esc_attr( $field_config['class'] ); ?>"
				value = "<?php echo esc_attr( $value['field_title'] ); ?>"
				autocomplete = 'off'
				required
				placeholder="Enter a field name"
			/>
		</td>
		
		<td class="forminp forminp-<?php echo wp_kses_post( sanitize_title( $field_config['type'] ) ); ?> input-column" style="text-align:center;padding:10px;">
			<select style="width:100%" name="<?php echo esc_attr( $field_config['id'] ); ?>[<?php echo esc_attr( $index ); ?>][field_type]" class="<?php echo esc_attr( $field_config['class'] ); ?>" required>
				<option <?php echo ( isset( $value['field_type'] ) && '' == $value['field_type'] ) ? "selected='selected'" : ''; ?> value="">Select Type Of Field</option>
				<option <?php echo ( isset( $value['field_type'] ) && 'text' == $value['field_type'] ) ? "selected='selected'" : ''; ?> value="text"><?php echo esc_html__( 'Text ', 'b2b-ecommerce' ); ?></option>
				<option <?php echo ( isset( $value['field_type'] ) && 'date' == $value['field_type'] ) ? "selected='selected'" : ''; ?> value="date"><?php echo esc_html__( 'Date ', 'b2b-ecommerce' ); ?></option>
				<option <?php echo ( isset( $value['field_type'] ) && 'number' == $value['field_type'] ) ? "selected='selected'" : ''; ?> value="number"><?php echo esc_html__( 'Number ', 'b2b-ecommerce' ); ?></option>
				<option <?php echo ( isset( $value['field_type'] ) && 'email' == $value['field_type'] ) ? "selected='selected'" : ''; ?>  value="email"><?php echo esc_html__( 'Email ', 'b2b-ecommerce' ); ?></option>
				<option <?php echo ( isset( $value['field_type'] ) && 'role' == $value['field_type'] ) ? "selected='selected'" : ''; ?>  value="role"><?php echo esc_html__( 'Role ', 'b2b-ecommerce' ); ?></option>
			</select>
			
		</td>
		
		<td class="forminp forminp-<?php echo wp_kses_post( sanitize_title( $field_config['type'] ) ); ?> input-column" style="text-align:center;padding:0px;">
			<input
				name="<?php echo esc_attr( $field_config['id'] ); ?>[<?php echo esc_attr( $index ); ?>][is_required]"
				type="checkbox"
				dir="ltr"
				style="<?php echo esc_attr( $field_config['css'] ); ?>"
				class="<?php echo esc_attr( $field_config['class'] ); ?>"
				value = "1"
				<?php echo isset( $value['is_required'] ) ? "checked='checked'" : ''; ?>
				autocomplete = 'off'
			/>
		</td>
		<td class="forminp forminp-<?php echo wp_kses_post( sanitize_title( $field_config['type'] ) ); ?> input-column" style="text-align:center;padding:0px;">
			<input
				name="<?php echo esc_attr( $field_config['id'] ); ?>[<?php echo esc_attr( $index ); ?>][is_visible]"
				type="checkbox"
				dir="ltr"
				style="<?php echo esc_attr( $field_config['css'] ); ?>"
				class="<?php echo esc_attr( $field_config['class'] ); ?>"
				value = "1"
				<?php echo isset( $value['is_visible'] ) ? "checked='checked'" : ''; ?>
				autocomplete = 'off'
			/>
		</td >
		<?php if ( 6 <= $index ) : ?>
			<td>
				<span style="position: absolute;left: 60%;" class='dashicons dashicons-no-alt remove-field' title='Remove Field'></span></td>
		<?php endif; ?>
		<input type="hidden" class="b2be_signup_field_id" name="<?php echo esc_attr( $field_config['id'] ); ?>[<?php echo esc_attr( $index ); ?>][field_id]" value="<?php echo wp_kses_post( empty( $default_fields[ $index ]['field_id'] ) ) ? esc_attr( str_replace( ' ', '_', esc_attr( strtolower( $value['field_title'] ) ) ) ) : esc_attr( $default_fields[ $index ]['field_id'] ); ?>" >
	</tr>
	<?php
	$index++;
}

