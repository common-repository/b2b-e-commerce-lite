<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_Required_Login' ) ) {
	/**
	 * Class B2BE_Required_Login.
	 */
	class B2BE_Required_Login {

		/**
		 * Construct.
		 */
		public function __construct() {

			add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'single_sign_up_button' ) );

			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'single_sign_up_button_loop' ), 30, 2 );

			add_filter( 'woocommerce_get_price_html', array( $this, 'hide_price_for_non_login' ), 100, 2 );

			add_action( 'wp_login', array( $this, 'return_back_to_page' ), 10, 2 );

		}

		/**
		 * Redirect back to where the user clicked sign in button.
		 *
		 * @param array  $user_login User login details.
		 * @param object $user User object.
		 */
		public function return_back_to_page( $user_login, $user ) {

			if ( isset( $_GET['returnPage'] ) ) {

				wp_redirect( base64_decode( sanitize_text_field( wp_unslash( $_GET['returnPage'] ) ) ) );
				exit;

			}

		}

		/**
		 * Hide Button On Prodct Single Page For Required Login.
		 */
		public function single_sign_up_button() {
			global $product;

			if ( 'yes' != get_option( 'codup_enable_hide_catalogue' ) ) {
				return;
			}

			if ( ! is_user_logged_in() ) {
				$enabled             = b2be_is_required_login( $product );
				$my_account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

				if ( $enabled ) {
					echo '<style>button.single_add_to_cart_button{ display: none !important;}</style>';
					echo '<style>.auto-add-sample{ display: none !important;}</style>';
					echo '<style>.cwppe-add-to-cart.btn.btn-info{ display: none !important;}</style>';
					?>
					<a style="width:max-content" href="<?php echo wp_kses_post( $my_account_page_url . '?returnPage=' . base64_encode( get_the_permalink() ) ); ?>" name="required_login" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_required_login_button button alt"><?php echo wp_kses_post( apply_filters( 'b2be_signin_to_view_button_text', esc_html__( 'Sign In To View', 'b2b-ecommerce' ) ) ); ?></a>
					<?php
				}
			}

		}

		/**
		 * Hide Button On Cart Page For Required Login.
		 */
		public function single_sign_up_button_loop() {

			global $product;

			if ( 'yes' != get_option( 'codup_enable_hide_catalogue' ) ) {
				return;
			}

			if ( ! is_user_logged_in() ) {

				$enabled             = b2be_is_required_login( $product );
				$my_account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

				if ( $enabled ) {
					echo ( '<style>.post-' . esc_attr( $product->get_id() ) . ' .button.add_to_cart_button{display: none !important ;}</style>' );
					echo ( '<style>.post-' . esc_attr( $product->get_id() ) . ' .button.product_type_grouped{display: none !important ;}</style>' );

					?>
					<a style="width:max-content" href="<?php echo wp_kses_post( $my_account_page_url . '?returnPage=' . base64_encode( 'shop' ) ); ?>" name="required_login" value="<?php echo esc_attr( $product->get_id() ); ?>"  class="single_required_login_button button alt"><?php echo wp_kses_post( apply_filters( 'b2be_signin_to_view_button_text', esc_html__( 'Sign In To View', 'b2b-ecommerce' ) ) ); ?></a>
					<?php
				}
			}

		}

		/**
		 * Hide price for required login.
		 *
		 * @param string $price Current Price.
		 * @param object $product Current Product.
		 */
		public function hide_price_for_non_login( $price, $product ) {

			if ( 'yes' != get_option( 'codup_enable_hide_catalogue' ) ) {
				return $price;
			}

			if ( ! is_user_logged_in() ) {
				$enabled = b2be_is_required_login( $product );
				if ( $enabled ) {
					echo '';
				} else {
					return $price;
				}
			} else {
				return $price;
			}
		}
	}
}
new B2BE_Required_Login();
