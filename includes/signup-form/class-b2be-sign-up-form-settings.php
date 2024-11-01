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
class Codup_Sign_Up_Form_Settings {

	/**
	 * Function Calculate Shipping.
	 */
	public static function init() {

		add_action( 'woocommerce_admin_field_codup_signup_fields', __CLASS__ . '::output_sign_up_form_fields' );
		add_action( 'woocommerce_admin_field_codup_signup_fields_footer', __CLASS__ . '::output_sign_up_form_fields_footer' );

	}


	/**
	 * Return RFQ setting fields.
	 *
	 * @return type
	 */
	public static function get_settings() {

		$settings = self::get_sign_up_form_fields();

		return $settings;
	}

	/**
	 * Return User Role setting fields.
	 *
	 * @return type
	 */
	public static function get_sign_up_form_fields() {
		$fields = array(
			'general_title'  => array(
				'title' => __( 'Signup Form', 'b2b-ecommerce' ),
				'type'  => 'title',
				'id'    => 'codup_signup-fields_title',
				'desc'  => __( 'Copy this short code <span style="font-weight:800;">[b2be_signup_form]</span>  and paste it anywhere to generate signup form.', 'b2b-ecommerce' ),
			),
			'admin_apporval' => array(
				'name'     => __( 'Required Approval', 'b2b-ecommerce' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Enable required approval', 'b2b-ecommerce' ),
				'desc_tip' => __( 'Makes it mandatory to get approval from admin before the user is created through the Signup form', 'b2b-ecommerce' ),
				'id'       => 'codup_signup_admin_apporval',
				'custom_attributes' => array(
					'disabled' => 'disabled',
				),
			),

		);
		$signup_fields = get_option( 'codup_ecommerce_signup_field' );

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
		}

		$fields['signup_fields'] = array(
			'type'   => 'codup_signup_fields',
			'id'     => 'codup_ecommerce_signup_field',
			'class'  => 'codup-ecommerce-signup-field-mode',
			'css'    => '',
			'signup' => $signup_fields,
		);

		$fields['signup_footer'] = array(
			'type' => 'codup_signup_fields_footer',
			'id'   => 'codup-signup-field-footer',
		);

		$fields['general_title_end'] = array(
			'type' => 'sectionend',
			'id'   => 'codup_user_role_title',
		);
		return $fields;
	}

	/**
	 * Output User Role setting fields.
	 *
	 * @param array $field_config Role Based Settings Tab array.
	 */
	public static function output_sign_up_form_fields( $field_config ) {
		include B2BE_PLUGIN_DIR . '/includes/admin/signup-form/views/sign-up-form-fields.php';
	}

	/**
	 * Output User Role setting fields footer.
	 *
	 * @param array $field_config Role Based Settings Tab array.
	 */
	public static function output_sign_up_form_fields_footer( $field_config ) {
		include B2BE_PLUGIN_DIR . '/includes/admin/signup-form/views/sign-up-form-footer.php';
	}

}
new Codup_Sign_Up_Form_Settings();
