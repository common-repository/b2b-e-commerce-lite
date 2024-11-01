<?php
/**
 * Codup RFQ Emails.
 *
 * @package codupio-request-for-quote-d659b8ba1ef2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_RFQ_Emails' ) ) {
	/**
	 * B2BE_RFQ_Emails.
	 */
	class B2BE_RFQ_Emails {

		/**
		 * Construct.
		 */
		public function __construct() {
			add_filter( 'woocommerce_locate_core_template', array( $this, 'locate_core_template' ), 10, 3 );
			add_filter( 'woocommerce_email_classes', array( $this, 'add_request_for_quote_email' ) );
			add_action( 'b2be_rfq_quote_sumitted', array( $this, 'trigger_quote_submitted_email' ), 11, 2 );
			add_action( 'b2be_rfq_created', array( $this, 'trigger_new_rfq_email' ), 11, 1 );
			add_action( 'b2be_rfq_comment_created', array( $this, 'trigger_new_comment_email' ), 11, 1 );
			add_action( 'b2be_rfq_quote_marked_accepted_email', array( $this, 'trigger_quoted_accepted' ), 10, 2 );
			add_action( 'b2be_rfq_quote_marked_rejected', array( $this, 'trigger_quoted_rejected' ), 10, 2 );
			add_action( 'b2be_rfq_quote_marked_need_revision', array( $this, 'trigger_quote_need_revision' ), 10, 2 );

			add_action( 'sfg_signup_rejected', array( $this, 'trigger_signup_rejected_email' ), 10, 2 );
			add_action( 'sfg_signup_accepted', array( $this, 'trigger_signup_accepted_email' ), 10, 2 );
			add_action( 'sfg_signup_pending', array( $this, 'trigger_signup_pending_email' ), 10, 2 );
			add_action( 'sfg_signup_email_to_admin', array( $this, 'trigger_signup_email_to_admin' ), 10, 2 );

		}
		/**
		 * Core Email Templates.
		 * Core files.
		 *
		 * @param string $core_file core files .
		 * Templates.
		 * @param string $template templates .
		 * Template base.
		 * @param string $template_base template base .
		 */
		public function locate_core_template( $core_file, $template, $template_base ) {

			$rfq_email_template = array(
				'emails/request-for-qoute-on-message-send.php',
				'emails/plain/request-for-qoute-on-message-send.php',
				'emails/request-for-qoute-on-edit.php',
				'emails/request-for-qoute-on-status-change.php',
				'emails/plain/request-for-qoute-on-edit.php',
				'emails/customer-quote-submitted.php',
				'emails/admin-new-rfq.php',
				'emails/new-rfq-email-to-customer.php',
				'emails/admin-new-comment.php',
				'emails/customer-new-comment.php',
				'emails/admin-rfq-accepted.php',
				'emails/admin-rfq-rejected.php',
				'emails/admin-rfq-need-revision.php',
			);

			if ( in_array( $template, $rfq_email_template ) ) {
				$core_file = trailingslashit( B2BE_TEMPLATE_DIR ) . $template;
			}

			return $core_file;
		}
		/**
		 * Add Emails File.
		 *
		 * Core files.
		 *
		 * @param string $email_classes core files .
		 */
		public function add_request_for_quote_email( $email_classes ) {

			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-wc-email-new-rfq.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-wc-email-new-rfq-email-to-customer.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-wc-email-new-comment.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-wc-email-new-admin-comment.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-b2be-request-for-qoute-on-message-send.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-b2be-request-for-qoute-status-change.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-rfq-email-quote-accepted.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-rfq-email-quote-rejected.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-rfq-email-customer-quote-submitted.php';
			require B2BE_PLUGIN_DIR . '/includes/emails/request-for-quote/class-rfq-email-quote-need-revision.php';

			$email_classes['codup_request_for_qoute_on_message_send'] = new Codup_Request_For_Qoute_On_Message_Send();
			$email_classes['codup_request_for_qoute_status_change']   = new Codup_Request_For_Qoute_Status_Change();
			$email_classes['rfq_quote_submitted']                     = new RFQ_Email_Customer_Quote_Submitted();
			$email_classes['new_rfq_submission_email_to_customer']    = new WC_Email_New_RFQ_Email_To_Customer();
			$email_classes['new_rfq_submitted']                       = new WC_Email_New_RFQ();
			$email_classes['new_comment_submitted']                   = new WC_Email_New_Comment();
			$email_classes['new_admin_comment_submitted']             = new WC_Email_New_Admin_Comment();
			$email_classes['rfq_accepted']                            = new RFQ_Email_Quote_Accepted();
			$email_classes['rfq_rejected']                            = new RFQ_Email_Quote_Rejected();
			$email_classes['rfq_need_revision']                       = new RFQ_Email_Quote_Need_Revision();

			return $email_classes;

		}

		/**
		 * Trigger.
		 *
		 * @param int    $quote_id Quote Id .
		 * @param object $quote Quote object .
		 */
		public function trigger_quote_submitted_email( $quote_id, $quote ) {
			WC()->mailer()->emails['rfq_quote_submitted']->trigger( $quote_id, $quote );
		}
		/**
		 * Trigger.
		 *
		 * @param object $quote Quote object .
		 */
		public function trigger_new_rfq_email( $quote ) {
			WC()->mailer()->emails['new_rfq_submitted']->trigger( $quote );
			WC()->mailer()->emails['new_rfq_submission_email_to_customer']->trigger( $quote );
		}
		/**
		 * Trigger.
		 *
		 * @param int    $quote_id Quote_Id .
		 * @param object $quote Quote object .
		 */
		public function trigger_quoted_accepted( $quote_id, $quote ) {
			WC()->mailer()->emails['rfq_accepted']->trigger( $quote_id, $quote );
		}
		/**
		 * Trigger.
		 *
		 * @param int    $quote_id Quote ID .
		 * @param object $quote Quote object .
		 */
		public function trigger_quoted_rejected( $quote_id, $quote ) {
			WC()->mailer()->emails['rfq_rejected']->trigger( $quote_id, $quote );
		}
		/**
		 * Trigger.
		 *
		 * @param int    $quote_id Quote ID .
		 * @param object $quote Quote object .
		 */
		public function trigger_quote_need_revision( $quote_id, $quote ) {
			WC()->mailer()->emails['rfq_need_revision']->trigger( $quote_id, $quote );
		}
		/**
		 * Trigger.
		 *
		 * @param object $quote Quote object .
		 */
		public function trigger_new_comment_email( $quote ) {
			WC()->mailer()->emails['new_comment_submitted']->trigger( $quote );
		}
		/**
		 * Trigger.
		 *
		 * @param object $quote Quote object .
		 */
		public function trigger_new_admin_comment_email( $quote ) {
			WC()->mailer()->emails['new_admin_comment_submitted']->trigger( $quote );
		}
	}

}
new B2BE_RFQ_Emails();
