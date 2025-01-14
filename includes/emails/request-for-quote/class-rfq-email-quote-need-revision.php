<?php
/**
 * Functions used by plugins
 *
 * @since 2.5.0
 * @package woocomerce/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RFQ_Email_Quote_Need_Revision', false ) ) :
	/**
	 * RFQ_Email_Quote_Need_Revision
	 */
	class RFQ_Email_Quote_Need_Revision extends WC_Email {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id            = 'quote_need_revision';
			$this->title         = __( 'Quote needs Revision', 'b2b-ecommerce' );
			$this->description   = __( 'Quote needs revision emails are sent to chosen recipient(s) when quote have been marked need revision by customer (if they were previously quoted).', 'b2b-ecommerce' );
			$this->template_html = 'emails/admin-rfq-need-revision.php';
			// $this->template_plain = 'emails/plain/admin-rfq-need-revision.php';
			$this->placeholders = array(
				'{quote_date}'      => '',
				'{quote_number}'    => '',
				'{quote_full_name}' => '',
			);

			// Call parent constructor.
			parent::__construct();

			// Other settings.
			$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
		}

		/**
		 * Get email subject.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_subject() {
			return __( '[{site_title}]: Quote #{quote_number} needs revision', 'b2b-ecommerce' );
		}

		/**
		 * Get email heading.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_heading() {
			return __( 'Quote : #{quote_number} Needs Revision', 'b2b-ecommerce' );
		}

		/**
		 * Trigger the sending of this email.
		 *
		 * @param int  $quote_id The quote ID.
		 * @param bool $quote The Quote.
		 */
		public function trigger( $quote_id, $quote = false ) {
			$this->setup_locale();

			if ( $quote && ! is_a( $quote, 'B2BE_RFQ_Quote' ) ) {
				$quote = b2be_get_quote( $quote );
			}

			if ( is_a( $quote, 'B2BE_RFQ_Quote' ) ) {
				$this->object                         = $quote;
				$this->placeholders['{quote_date}']   = wc_format_datetime( $this->object->get_date_created() );
				$this->placeholders['{quote_number}'] = $this->object->get_quote_number();
								$this->placeholders['{quote_billing_full_name}'] = $this->object->get_formatted_full_name();
			}

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}

			$this->restore_locale();
		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'quote'         => $this->object,
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => true,
					'plain_text'    => false,
					'email'         => $this,
				),
				'b2b-ecommerce-for-woocommerce',
				B2BE_TEMPLATE_DIR
			);
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			return wc_get_template_html(
				$this->template_plain,
				array(
					'quote'         => $this->object,
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => true,
					'plain_text'    => true,
					'email'         => $this,
				),
				'b2b-ecommerce-for-woocommerce',
				B2BE_TEMPLATE_DIR
			);
		}

		/**
		 * Default content to show below main email content.
		 *
		 * @since 1.1.5.0
		 * @return string
		 */
		public function get_default_additional_content() {
			return __( 'Thanks for reading.', 'b2b-ecommerce' );
		}

		/**
		 * Initialise settings form fields.
		 */
		public function init_form_fields() {
			/* translators: %s: list of placeholders */
			$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'b2b-ecommerce' ), '<code>' . esc_html( implode( '</code>, <code>', array_keys( $this->placeholders ) ) ) . '</code>' );
			$this->form_fields = array(
				'enabled'            => array(
					'title'   => __( 'Enable/Disable', 'b2b-ecommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'b2b-ecommerce' ),
					'default' => 'yes',
				),
				'recipient'          => array(
					'title'       => __( 'Recipient(s)', 'b2b-ecommerce' ),
					'type'        => 'text',
					/* translators: %s: admin email */
					'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'b2b-ecommerce' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => true,
				),
				'subject'            => array(
					'title'       => __( 'Subject', 'b2b-ecommerce' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => $placeholder_text,
					'placeholder' => $this->get_default_subject(),
					'default'     => '',
				),
				'heading'            => array(
					'title'       => __( 'Email heading', 'b2b-ecommerce' ),
					'type'        => 'text',
					'desc_tip'    => true,
					'description' => $placeholder_text,
					'placeholder' => $this->get_default_heading(),
					'default'     => '',
				),
				'additional_content' => array(
					'title'       => __( 'Additional content', 'b2b-ecommerce' ),
					'description' => __( 'Text to appear below the main email content.', 'b2b-ecommerce' ) . ' ' . $placeholder_text,
					'css'         => 'width:400px; height: 75px;',
					'placeholder' => __( 'N/A', 'b2b-ecommerce' ),
					'type'        => 'textarea',
					'default'     => $this->get_default_additional_content(),
					'desc_tip'    => true,
				),
				'email_type'         => array(
					'title'       => __( 'Email type', 'b2b-ecommerce' ),
					'type'        => 'select',
					'description' => __( 'Choose which format of email to send.', 'b2b-ecommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
					'desc_tip'    => true,
				),
			);
		}
	}

endif;

return new RFQ_Email_Quote_Need_Revision();
