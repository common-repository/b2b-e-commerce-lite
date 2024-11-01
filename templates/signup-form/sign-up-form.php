<?php
/**
 * Sign Up Form
 *
 * This template can be overridden by copying it to yourtheme/b2b-ecommerce-for-woocommerce/signup-form/sign-up-form.php
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package B2b Ecommerce For Woocommerce/Templates
 * @version 1.3.9.6
 */

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php do_action( 'b2be_before_signup_form', $sign_up_fields ); ?>
<?php wc_print_notices(); ?>
<form method="POST">
	<div class="cwl-tier-row" >
	<?php
	foreach ( $sign_up_fields as $key => $value ) {
		if ( isset( $value['is_visible'] ) ) :
			?>
			<p class="forminp input-column" data-role-number="<?php echo esc_attr( $key ); ?>">
				<label for="[<?php echo esc_attr( $key ); ?>][field_title]">
					<?php echo wp_kses_post( apply_filters( 'b2be_signup_form_' . $value['field_id'], esc_attr( $value['field_title'] ) ) ); ?>
				</label>
				<?php if ( isset( $value['field_type'] ) && 'role' == $value['field_type'] ) { ?> 
					<br>	
					<select style="width:100%" name="codup_wc_sfg[<?php echo esc_attr( $value['field_id'] ); ?>]" <?php echo isset( $value['is_required'] ) ? 'required' : ''; ?>  >
						<option value="" ><?php echo esc_html__( 'Select A Role...', 'b2b-ecommerce' ); ?></option>
						<option value="<?php echo 'customer'; ?>" > <?php echo 'Customer'; ?> </option>
						<?php
						$b2b_user_roles = b2be_get_custom_added_roles();
						foreach ( $b2b_user_roles as $b2b_user_role_id => $b2b_user_role ) {
							?>
							<option value="<?php echo wp_kses_post( $b2b_user_role_id ); ?>" > <?php echo wp_kses_post( $b2b_user_role ); ?> </option>
							<?php
						}
						?>
					</select>
				<?php } else { ?>
					<input
						name="codup_wc_sfg[<?php echo esc_attr( $value['field_id'] ); ?>]"
						type="<?php echo esc_attr( $value['field_type'] ); ?>"
						dir="ltr"
						style="width:100%"
						class=""
						autocomplete = 'off'
						<?php echo isset( $value['is_required'] ) ? "required='required'" : ''; ?> 
						<?php echo ( isset( $value['field_type'] ) && 'number' == $value['field_type'] ) ? 'min="0"' : ''; ?> 
					/>
				<?php } ?>
			</p>
			
			<?php
		endif;
	}
	?>
	<input type="submit" class="sign_up_button" id="sign_up_button" name="sign_up_button" value="<?php echo wp_kses_post( apply_filters( 'b2be_signup_form_button_text', 'Sign Up' ) ); ?>">
	</div>
</form>
<?php do_action( 'b2be_after_signup_form', $sign_up_fields ); ?>
