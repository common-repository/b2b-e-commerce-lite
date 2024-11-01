<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Codup_Sign_Up_Form' ) ) {
	/**
	 * Class Codup_Sign_Up_Form.
	 */
	class Codup_Sign_Up_Form {

		/**
		 * Cart Variable.
		 */
		public function __construct() {

			Codup_Sign_Up_Form_Settings::init();
			add_shortcode( 'b2be_signup_form', array( $this, 'b2be_sign_up_form' ) );
			add_action( 'wp_loaded', array( $this, 'submit_sign_up_form' ), 10, 1 );
			add_action( 'woocommerce_edit_account_form', array( $this, 'add_field_edit_account_form' ) );
			add_action( 'woocommerce_save_account_details', array( $this, 'save_account_details' ) );
			add_action( 'init', array( $this, 'b2be_signup_customizing_email_template' ) );	
		
		}
		
		/**
		 * Initializing customizing hook.
		 */
		public function b2be_signup_customizing_email_template() {
			
			add_filter( 'wp_new_user_notification_email', array( $this, 'b2be_sign_up_form_reset_email_message' ), 10, 3 );
			
		}
		
		/**
		 * Sign Up form .
		 */
		public function b2be_sign_up_form() {

			if ( ! is_user_logged_in() ) {

				$field_config['signup'] = apply_filters( 'b2be_sign_up_fields', get_option( 'codup_ecommerce_signup_field' ) );
				if ( ! empty( $field_config['signup'] ) && count( $field_config['signup'] ) != 0 ) {

					wc_get_template(
						'signup-form/sign-up-form.php',
						array(
							'sign_up_fields' => $field_config['signup'],
						),
						'b2b-ecommerce-for-woocommerce',
						B2BE_PLUGIN_DIR . '/templates/'
					);

				} else {

					$output = __( 'There Is Nothing To Show Please Select Some Fields', 'b2b-ecommerce' );
					return $output;

				}
			}
		}

		/**
		 * Submit Sign Up Form .
		 */
		public function submit_sign_up_form() {

			if ( isset( $_POST['wcb2be_signup_nonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_POST['wcb2be_signup_nonce'] ) );
				wp_verify_nonce( $nonce, 'wcb2be_signup_setting' );
			}

			$codup_wc_sfgs = ! empty( $_POST['codup_wc_sfg'] ) ? filter_input( INPUT_POST, 'codup_wc_sfg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) : '';

			if ( ! empty( $codup_wc_sfgs ) ) {

				$signup_form_fields = get_option( 'codup_ecommerce_signup_field', true );
				$i                  = 0;
				foreach ( $signup_form_fields as $key => $signup_form_field ) {

					if ( isset( $codup_wc_sfgs[ $signup_form_field['field_id'] ] ) ) {

						$custom_signup_fields[ $signup_form_field['field_id'] ] = array(
							'field_title'    => $signup_form_field['field_title'],
							'field_type'     => $signup_form_field['field_type'],
							'field_required' => isset( $signup_form_field['is_required'] ) ? $signup_form_field['is_required'] : 0,
							'field_value'    => $codup_wc_sfgs[ $signup_form_field['field_id'] ],
						);

					}

					$i++;
				}

				$username       = sanitize_text_field( wp_unslash( $codup_wc_sfgs['user_name'] ) );

				if ( username_exists( $username ) ) {
					wc_add_notice( __( 'You Cannot Sign Up.. Username Already Exist!', 'b2b-ecommerce' ), 'error' );
					return;
				}

				$first_name     = isset( $codup_wc_sfgs['first_name'] ) ? sanitize_text_field( wp_unslash( $codup_wc_sfgs['first_name'] ) ) : '';
				$last_name      = isset( $codup_wc_sfgs['last_name'] ) ? sanitize_text_field( wp_unslash( $codup_wc_sfgs['last_name'] ) ) : '';
				$b2be_user_role = isset( $codup_wc_sfgs['role'] ) ? sanitize_text_field( wp_unslash( $codup_wc_sfgs['role'] ) ) : '';

				if ( ! empty( $b2be_user_role ) && ! b2be_role_exists( $b2be_user_role ) ) {

					foreach ( $codup_wc_sfgs as $key => $codup_wc_sfg ) {

						if ( b2be_role_exists( $codup_wc_sfg ) ) {

							$b2be_user_role = $codup_wc_sfg;

						}
					}
				}

				$email = sanitize_text_field( wp_unslash( $codup_wc_sfgs['email'] ) );
				if ( email_exists( $email ) ) {
					wc_add_notice( __( 'You Cannot Sign Up.. Email Already Exist!', 'b2b-ecommerce' ), 'error' );
					return;
				}
				$characters      = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$random_password = '';
				$random_id       = '';

				for ( $i = 0; $i < 32; $i++ ) {
					$index            = rand( 0, strlen( $characters ) - 1 );
					$random_password .= $characters[ $index ];
				}

				for ( $i = 0; $i < 3; $i++ ) {
					$index      = rand( 0, strlen( $characters ) - 1 );
					$random_id .= $characters[ $index ];
				}

				$userdata = array(
					'user_pass'  => $random_password,
					'user_login' => ! empty( $username ) ? $username : $first_name . $last_name . $random_id,
					'user_email' => $email,
					'first_name' => ! empty( $first_name ) ? $first_name : '',
					'last_name'  => ! empty( $last_name ) ? $last_name : '',
					'role'       => ! empty( $b2be_user_role ) && b2be_role_exists( $b2be_user_role ) ? $b2be_user_role : 'customer',

				);
				$new_user_id = wp_insert_user( $userdata );
				$user        = get_user_by( 'id', $new_user_id );

				update_user_meta( $new_user_id, 'sfg_user_signup_information', $custom_signup_fields );

				do_action( 'after_sfg_form', $custom_signup_fields );

				wp_new_user_notification( $new_user_id, null, 'both' );
				update_user_meta( $new_user_id, 'sign_up_request', 'sign_up_approval' );

				wc_add_notice( __( 'User Registered Successfully.Please check your email to set password.', 'b2b-ecommerce-lite' ), 'success' );
				return;
			}
		}

		/**
		 * Add field
		 */
		public function add_field_edit_account_form() {

			$sfg_user_signup_information = get_user_meta( get_current_user_id(), 'sfg_user_signup_information', true );
			$fields_to_skip              = array( 'user_name', 'first_name', 'last_name', 'email', 'role' );

			if ( empty( $sfg_user_signup_information ) ) {
				return;
			}

			foreach ( $sfg_user_signup_information as $key => $value ) {
				if ( in_array( $key, $fields_to_skip ) ) {
					continue;
				}
				woocommerce_form_field(
					$key,
					array(
						'type'     => $value['field_type'],
						'required' => $value['field_required'],
						'label'    => $value['field_title'],
					),
					$value['field_value']
				);

			}

		}

		/**
		 * Save field value
		 *
		 * @param int $user_id User Id.
		 */
		public function save_account_details( $user_id ) {

			if ( isset( $_POST['wcb2be_account_nonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_POST['wcb2be_account_nonce'] ) );
				wp_verify_nonce( $nonce, 'wcb2be_account_page' );
			}

			$sfg_user_signup_information = get_user_meta( $user_id, 'sfg_user_signup_information', true );

			foreach ( $sfg_user_signup_information as $key => $value ) {

				if ( 'user_name' == $key || 'role' == $key ) {
					continue;
				}
				if ( isset( $_POST[ 'account_' . $key ] ) ) {
					$sfg_user_signup_information[ $key ]['field_value'] = sanitize_text_field( wp_unslash( $_POST[ 'account_' . $key ] ) );
				} elseif ( isset( $_POST[ $key ] ) ) {
					$sfg_user_signup_information[ $key ]['field_value'] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
				} else {
					$sfg_user_signup_information[ $key ]['field_value'] = '';
				}
			}
			update_user_meta( $user_id, 'sfg_user_signup_information', $sfg_user_signup_information );

		}

		/**
		 * Customize Reset Password email template.
		 * 
		 * @param array $wp_new_user_notification_email Email details. 
		 * @param object $user Current user object.
		 * @param string $blogname Blog Name.
		 */
		public function b2be_sign_up_form_reset_email_message( $wp_new_user_notification_email, $user, $blogname ) {
		
			$key = get_password_reset_key( $user );
			if ( is_wp_error( $key ) ) {
				return;
			}

			$message = '';
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			/* translators: %s: site name */
			$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
			/* translators: %s: user login */
			$message .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
			$message .= __( 'Thank You For Registeration. Please ' );
			$message .= '<a target="_blank" href="'.network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ).'">Click Here</a>';
			$message .= __( ' to set your password' ) . "\r\n\r\n";
		
			$wp_new_user_notification_email['subject'] = __( 'Set Your Password');
			
			$wp_new_user_notification_email['message'] = $message;
			$wp_new_user_notification_email['headers'] = array('Content-Type: text/html; charset=UTF-8');
		
			return $wp_new_user_notification_email;

		}

	}
}
new Codup_Sign_Up_Form();
