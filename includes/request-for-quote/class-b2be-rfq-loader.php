<?php
/**
 * Class B2BE_RFQ_Loader file.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'B2BE_RFQ_Loader' ) ) {

	/**
	 *  Class B2BE_RFQ_Loader.
	 */
	class B2BE_RFQ_Loader {
		/**
		 *  Constructor.
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_backend_scripts' ) );
			$this->includes();
		}

		/**
		 *  Function Includes.
		 */
		public function includes() {

			include_once 'class-b2be-rfq-quote-post-types.php';

			if ( ! is_admin() ) {
				include_once 'class-b2be-rfq-cart-shortcode.php';
				include_once 'class-b2be-rfq-form-handler.php';
			} elseif ( wp_doing_ajax() ) {
				include_once 'class-b2be-rfq-comments.php';

			} else {
				// inluding all admin classes here.
				include_once B2BE_PLUGIN_DIR . '/includes/admin/request-for-quote/class-b2be-rfq-admin.php';
			}
			include_once B2BE_PLUGIN_DIR . '/includes/admin/request-for-quote/class-rfq-meta-box-quote-items.php';
			include_once 'b2be-rfq-functions.php';
			include_once 'class-b2be-rfq-settings.php';
			include_once 'class-b2be-rfq-quote.php';
			include_once 'class-b2be-rfq.php';
			include_once 'class-b2be-rfq-emails.php';

		}
		/**
		 *  Register Frontend Scripts.
		 */
		public function register_frontend_scripts() {
			global $post;
			$post_slug = ! empty( $post ) ? $post->post_name : '';
			wp_enqueue_style( 'rfq_stylesheet', B2BE_ASSETS_DIR_URL . 'css/request-for-quote/admin-comment-metabox.css', '', true );
			wp_enqueue_script( 'dataTables.min-js', B2BE_ASSETS_DIR_URL . 'js/request-for-quote/jquery.dataTables.min.js', array( 'jquery' ), true );

			wp_enqueue_style( 'dataTables.min-css', B2BE_ASSETS_DIR_URL . 'css/request-for-quote/jquery.dataTables.min.css', '', true );
			wp_enqueue_script( 'rfq_script', B2BE_ASSETS_DIR_URL . 'js/request-for-quote/main.js', array( 'jquery' ), true );
			wp_localize_script(
				'rfq_script',
				'a_vars',
				array(
					'url'          => admin_url( 'admin-ajax.php' ),
					'page_slug'    => $post_slug,
					'rfq_cart_url' => b2be_get_rfq_cart_url(),
				)
			);
		}
		/**
		 *  Register Backend Scripts.
		 */
		public function register_backend_scripts() {
		
			wp_enqueue_script( 'b2be-rfq-admin-settings', B2BE_ASSETS_DIR_URL . 'js/request-for-quote/admin/quote-settings.js', array( 'jquery' ), true );
			wp_register_style( 'b2be-rfq-product-category-css', B2BE_ASSETS_DIR_URL . 'css/request-for-quote/admin/b2be-rfq-product-category.css', '', rand() );
		
		}

	}
}
$b2be_rfq_loader = new B2BE_RFQ_Loader();
