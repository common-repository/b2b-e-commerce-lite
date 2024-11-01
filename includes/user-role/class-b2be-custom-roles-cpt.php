<?php
/**
 * File For B2b Ecommerce For Woocomerce custom Roles Post Type.
 *
 * @package class-b2be-custom-roles-cpt.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'Codup_Custom_Roles_CPT' ) ) {

	/**
	 * Main Class For Custom Role.
	 */
	class Codup_Custom_Roles_CPT {

		/**
		 * Main Function.
		 */
		public function __construct() {

			add_action( 'init', array( __CLASS__, 'register_custom_roles_post_type' ), 0 );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
			add_action( 'wp_trash_post', array( $this, 'b2be_delete_custom_role' ), 10 );
			add_action( 'admin_head', array( $this, 'b2be_custom_role_remove_cpt_trash_button' ), 10, 1 );
			add_filter( 'post_row_actions', array( $this, 'b2be_custom_role_remove_cpt_row_actions_post' ), 10, 2 );
			add_action( 'publish_codup-custom-roles', array( $this, 'b2be_add_custom_role' ), 10, 2 );
		
		}

		/**
		 * Register core post types.
		 */
		public static function register_custom_roles_post_type() {

			$labels = array(
				'name'               => _x( 'Custom Role', 'post type general name', 'b2b-ecommerce' ),
				'singular_name'      => _x( 'Role', 'post type singular name', 'b2b-ecommerce' ),
				'menu_name'          => _x( 'Custom Role', 'admin menu', 'b2b-ecommerce' ),
				'name_admin_bar'     => _x( 'Custom Role', 'add new on admin bar', 'b2b-ecommerce' ),
				'add_new'            => _x( 'Add New', 'role', 'b2b-ecommerce' ),
				'add_new_item'       => __( 'Add New Role', 'b2b-ecommerce' ),
				'new_item'           => __( 'New Role', 'b2b-ecommerce' ),
				'edit_item'          => __( 'Edit Role', 'b2b-ecommerce' ),
				'view_item'          => __( 'View Role', 'b2b-ecommerce' ),
				'all_items'          => __( 'All Roles', 'b2b-ecommerce' ),
				'search_items'       => __( 'Search Roles', 'b2b-ecommerce' ),
				'parent_item_colon'  => __( 'Parent Roles:', 'b2b-ecommerce' ),
				'not_found'          => __( 'No custom roles found.', 'b2b-ecommerce' ),
				'not_found_in_trash' => __( 'No custom roles found in Trash.', 'b2b-ecommerce' ),
			);
			$args   = array(
				'labels'             => $labels,
				'description'        => __( 'Roles for WooCommerce products.', 'b2b-ecommerce' ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'cc-roles' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 70,
				'menu_icon'          => 'dashicons-groups',
				'supports'           => array( 'title' ),
			);
			register_post_type( 'codup-custom-roles', $args );
		}

		/**
		 * Add WC Meta boxes.
		 */
		public function add_meta_boxes() {

			if ( isset( $_GET['post'] ) ) {
				add_meta_box( 'cc-custom-roles-rfq', sprintf( __( 'RFQ', 'b2b-ecommerce' ) ), array( $this, 'b2be_add_custom_role_rfq' ), 'codup-custom-roles', 'normal', 'high' );
				add_meta_box( 'cc-custom-roles-shipping-exempts', sprintf( __( 'Shipping Exemption', 'b2b-ecommerce' ) ), array( $this, 'b2be_add_custom_role_shipping_exempts' ), 'codup-custom-roles', 'normal', 'high' );
				add_meta_box( 'cc-custom-roles-tax-exempts', sprintf( __( 'Tax Exemption', 'b2b-ecommerce' ) ), array( $this, 'b2be_add_custom_role_tax_exempts' ), 'codup-custom-roles', 'normal', 'high' );
				add_meta_box( 'cc-custom-roles-custom-payment-method', sprintf( __( 'WooCommerce Payment Method(s)', 'b2b-ecommerce' ) ), array( $this, 'b2be_woocomerce_payment_fields' ), 'codup-custom-roles', 'normal', 'low' );
				add_meta_box( 'cc-custom-roles-default-payment-method', sprintf( __( 'B2B Ecommerce Payment Method(s)', 'b2b-ecommerce' ) ), array( $this, 'b2be_custom_role_has_term_payment_fields' ), 'codup-custom-roles', 'normal', 'low' );
				add_meta_box( 'cc-credit', sprintf( __( 'Credit Payment(s)', 'b2b-ecommerce' ) ), array( $this, 'b2be_add_custom_role_credit' ), 'codup-custom-roles', 'side' );
			}
			remove_meta_box( 'slugdiv', 'codup-custom-roles', 'normal' );
			remove_meta_box( 'astra_settings_meta_box', 'codup-custom-roles', 'side' );

		}

		/**
		 * Function to render fields.
		 *
		 * @param object $post Post object.
		 */
		public function b2be_add_custom_role_credit( $post ) {
			$post_id = $post->ID;
			include B2BE_PLUGIN_DIR . '/includes/admin/credit-payment/views/credit-fields.php';

		}

		/**
		 * Function to render fields.
		 *
		 * @param object $post Post object.
		 */
		public function b2be_add_custom_role_rfq( $post ) {

			$post_id = $post->ID;
			include B2BE_PLUGIN_DIR . '/includes/admin/user-role/views/b2be-user-role-fields.php';

		}

		/**
		 * Function to render shipping_exempts fields.
		 *
		 * @param object $post Post Object.
		 */
		public function b2be_add_custom_role_shipping_exempts( $post ) {

			$post_id          = $post->ID;
			$wc_shipping      = WC_Shipping::instance();
			$shipping_classes = $wc_shipping->get_shipping_classes();

			include B2BE_PLUGIN_DIR . '/includes/admin/user-role/views/b2be-user-role-shipping-exempts.php';
		}

		/**
		 * Function to render shipping_exempts fields.
		 *
		 * @param object $post Post Object.
		 */
		public function b2be_add_custom_role_tax_exempts( $post ) {

			$post_id     = $post->ID;
			$tax_classes = WC_Tax::get_tax_classes();

			include B2BE_PLUGIN_DIR . '/includes/admin/user-role/views/b2be-user-role-tax-exempts.php';
		}

		/**
		 * Function to delete custom roile.
		 *
		 * @param int $post_id Post Id.
		 */
		public function b2be_delete_custom_role( $post_id ) {

			if ( 'codup-custom-roles' != get_post_type( $post_id ) ) {
				return;
			}

			$default_roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'customer', 'shop_manager' );
			if ( in_array( explode( '__trashed', get_post( $post_id )->post_name )[0], $default_roles ) ) {

				wp_die( esc_html__( 'This is a WordPress default role. You cannot delete it.', 'b2b-ecommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.

			} else {

				remove_role( explode( '__trashed', get_post( $post_id )->post_name )[0] );

			}

		}

		/**
		 * Function To remove trash buton from WordPress default roles.
		 */
		public function b2be_custom_role_remove_cpt_trash_button() {
			$current_screen = get_current_screen();

			// Hides the "Move to Trash" link on the post edit page.
			if ( 'post' === $current_screen->base && 'codup-custom-roles' === $current_screen->post_type ) {
				?><style>#post-body #post-body-content #titlediv .inside, #preview-action{  display: none !important;  }</style>
				<?php
				$default_roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'customer', 'shop_manager' );
				if ( isset( $_GET['post'] ) && in_array( explode( '__trashed', get_post( $_GET['post'] )->post_name )[0], $default_roles ) ) {
					?>
						<style>
							#delete-action { display: none !important; }
						</style>
						<script>
							jQuery(document).ready(function ($) {
								$( "input[name='post_title']" ).prop( "disabled", true );
							});
						</script>
					<?php
				}
				?>
				<style>
					.page-title-action { display:none !important; }
				</style>
				<?php
			}
		}

		/**
		 * Function to remove trash button from rows.
		 *
		 * @param array  $actions Actions array.
		 * @param object $post Post object.
		 */
		public function b2be_custom_role_remove_cpt_row_actions_post( $actions, $post ) {

			if ( 'codup-custom-roles' === $post->post_type ) {
				$default_roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'customer', 'shop_manager' );
				if ( in_array( explode( '__trashed', $post->post_name )[0], $default_roles ) ) {
					unset( $actions['clone'] );
					unset( $actions['trash'] );
				}
				unset( $actions['view'] );
				unset( $actions['inline hide-if-no-js'] );
			}

			return $actions;
		}

		/**
		 * Function to render default payment methods.
		 *
		 * @param object $post Post object.
		 */
		public function b2be_woocomerce_payment_fields( $post ) {

			$gateways = b2be_get_formatted_payment_methods( 'woocommerce' );

			if ( empty( $gateways ) ) {
				echo '<p style="font-size: 15px;text-align: center;">' . esc_html__( 'No Payment Methods are available.', 'b2b-ecommerce' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped. 
				return;
			}

			$global_has_term_enabled = get_option( 'b2be_rfq_enable_has_terms', 'no' );
			$post_id                 = $post->ID;
			if ( 'yes' === $global_has_term_enabled ) {
				?>
				<?php
				foreach ( $gateways as $id => $payment_method ) {
					?>
					<div class="options_group">
						<div class="form-field enable_rfq_field">
							<div class="title">
								<?php echo wp_kses_post( $payment_method->title ); ?>
							</div>
							<div class="desc-options">
								<input type="checkbox" name="<?php echo wp_kses_post( $payment_method->id ); ?>" id="<?php echo wp_kses_post( $payment_method->id ); ?>" value="1" <?php echo ( get_post_meta( $post_id, $payment_method->id, true ) == 'yes' ) ? 'checked="checked"' : ''; ?> >
								<span><?php echo esc_html__( 'This will enable ', 'b2b-ecommerce' ) . wp_kses_post( $payment_method->title ) . esc_html__( ' payment method ', 'b2b-ecommerce' ); ?></span>
							</div>
						</div>
					</div>
					<?php
				}
			} else {
				echo '<p style="font-size: 15px;text-align: center;">' . esc_html__( 'Enable The Payment Methods from ', 'b2b-ecommerce' ) . '<a href="' . wp_kses_post( site_url() ) . '/wp-admin/admin.php?page=wc-settings&tab=codup-b2b-ecommerce&section=codup-payment-method">' . esc_html__( 'Payment Method\'s settings', 'b2b-ecommerce' ) . '</a></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped. 
				return;
			}
		}

		/**
		 * Function to render default payment methods.
		 *
		 * @param object $post Post object.
		 */
		public function b2be_custom_role_has_term_payment_fields( $post ) {

			// Send empty parameter to get all payment method...
			$gateways = b2be_get_formatted_payment_methods( 'b2be_ecommerce' );

			if ( empty( $gateways ) ) {
				echo '<p style="font-size: 15px;text-align: center;">' . esc_html__( 'No Payment Methods are available.', 'b2b-ecommerce' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped. 
				return;
			}

			$global_has_term_enabled = get_option( 'b2be_rfq_enable_has_terms', 'no' );
			$post_id                 = $post->ID;
			if ( 'yes' === $global_has_term_enabled ) {
				?>
				<?php
				foreach ( $gateways as $id => $payment_method ) {
					?>
					<div class="options_group">
						<div class="form-field enable_rfq_field">
							<div class="title">
								<?php echo wp_kses_post( $payment_method->title ); ?>
							</div>
							<div class="desc-options">
								<input type="radio" name="b2be_role_based_payment_method" id="<?php echo wp_kses_post( $payment_method->id ); ?>" value="<?php echo wp_kses_post( $payment_method->id ); ?>" <?php echo ( get_post_meta( $post_id, 'b2be_role_based_payment_method', true ) == $payment_method->id ) ? 'checked="checked"' : ''; ?> >
								<span><?php echo esc_html__( 'This will enable ', 'b2b-ecommerce' ) . wp_kses_post( $payment_method->title ) . esc_html__( ' payment method ', 'b2b-ecommerce' ); ?></span>
							</div>
						</div>
					</div>
					<?php
				}
			} else {
				echo '<p style="font-size: 15px;text-align: center;">' . esc_html__( 'Enable The Payment Methods from ', 'b2b-ecommerce' ) . '<a href="' . wp_kses_post( site_url() ) . '/wp-admin/admin.php?page=wc-settings&tab=codup-b2b-ecommerce&section=codup-payment-method">' . esc_html__( 'Payment Method\'s settings', 'b2b-ecommerce' ) . '</a></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped. 
				return;
			}
		}
		
		/**
		 * Funtion to create custom roles.
		 *
		 * @param int    $post_id Post id.
		 * @param object $post Post Object.
		 */
		public function b2be_add_custom_role( $post_id, $post ) {

			if ( ! empty( $_POST['_wpnonce'] ) ) {
				wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) );
			}

			global $wp_roles;
			$default_roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'customer', 'shop_manager' );

			if ( ! b2be_role_exists( $post->post_name ) ) {
				add_role( $post->post_name, $post->post_title, array( 'read' => true ) );
			} else {
				if ( $post->post_title != $wp_roles->roles[ $post->post_name ]['name'] ) {
					if ( ! in_array( $post->post_name, $default_roles ) ) {
						remove_role( $post->post_name );
						add_role( $post->post_name, $post->post_title, array( 'read' => true ) );
					}
				}
			}
		}

	}
	new Codup_Custom_Roles_CPT();
}
