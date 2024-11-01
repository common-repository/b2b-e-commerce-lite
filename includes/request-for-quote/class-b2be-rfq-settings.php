<?php
/**
 * WC RFQ settings.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_RFQ_Settings' ) ) {

	/**
	 * Class B2BE_RFQ_Settings.
	 */
	class B2BE_RFQ_Settings {

		/**
		 * Settings Tab
		 *
		 * @var static $settings_tab Settings Tab.
		 */
		public static $settings_tab = 'b2be_rfq';

		/**
		 * Init Function.
		 */
		public static function init() {

			add_action( 'product_cat_add_form_fields', __CLASS__ . '::add_category_settings', 10, 1 );
			add_action( 'product_cat_edit_form_fields', __CLASS__ . '::edit_category_settings', 99, 1 );
			add_filter( 'woocommerce_product_data_tabs', __CLASS__ . '::rfq_product_settings_tab' );
			add_filter( 'woocommerce_product_data_panels', __CLASS__ . '::rfq_product_tab_content' );
		}


		/**
		 * Return RFQ setting fields.
		 *
		 * @return type
		 */
		public static function get_settings() {

			$add_to_rfq_btn_txt = get_option( 'b2be_rfq_add_to_rfq_button_text' );
			$accept_btn_txt     = get_option( 'b2be_rfq_accept_rfq_button_text' );
			$revision_btn_txt   = get_option( 'b2be_rfq_revison_rfq_button_text' );
			$view_btn_txt       = get_option( 'b2be_rfq_view_rfq_button_text' );
			$reject_btn_txt     = get_option( 'b2be_rfq_reject_rfq_button_text' );
			$pages_query        = get_pages();

			if ( '' != get_option( 'b2be_rfq_enable_rfq' ) ) {
				$value = get_option( 'b2be_rfq_enable_rfq' );
			} else {
				$value = 'yes';
			}

			if ( '' != get_option( 'b2be_rfq_disable_add_to_cart' ) ) {
				$cart_value = get_option( 'b2be_rfq_disable_add_to_cart' );
			} else {
				$cart_value = 'no';
			}

			foreach ( $pages_query as $key => $page_object ) {

				if ( 'my-account' == $page_object->post_name ) {
					continue;
				}
				$all_pages[ $page_object->ID ] = $page_object->post_title;

			}

			if ( empty( get_option( 'b2be_rfq_cart_page' ) ) ) {
				if ( ! empty( get_page_by_path( 'rfq' ) ) ) {
					$rfq_page_id = get_page_by_path( 'rfq' )->ID;
					update_option( 'b2be_rfq_cart_page', $rfq_page_id );
				}
			}

			$settings = array(
				'section_title'           => array(
					'name' => __( 'Global RFQ settings', 'b2b-ecommerce' ),
					'type' => 'title',
					'desc' => __( 'This will allow you to set the RFQ functionality globally. But you can also set it at product and category level.', 'b2b-ecommerce' ),
					'id'   => self::$settings_tab . '_section_title',
				),

				'enable_rfq'              => array(
					'name'  => __( 'Enable RFQ', 'b2b-ecommerce' ),
					'type'  => 'checkbox',
					'desc'  => ( 'yes' == $value ) ? __( 'RFQ is Enabled', 'b2b-ecommerce' ) : __( 'RFQ is Disabled', 'b2b-ecommerce' ),
					'id'    => self::$settings_tab . '_enable_rfq',
					'class' => 'class_enable_rfq',
					'value' => $value,
				),

				'disable_add_to_cart'     => array(
					'name'  => __( 'Disable Add to Cart', 'b2b-ecommerce' ),
					'type'  => 'checkbox',
					'desc'  => ( 'yes' == $cart_value ) ? __( 'Add To Cart Is Disabled', 'b2b-ecommerce' ) : __( 'Add To Cart Is Enabled', 'b2b-ecommerce' ),
					'id'    => self::$settings_tab . '_disable_add_to_cart',
					'value' => $cart_value,
				),

				'add_to_rfq_button_text'  => array(
					'name'     => __( 'Add To RFQ Button Label', 'b2b-ecommerce' ),
					'desc_tip' => __( 'This Will Change The Label Of Add to RFQ Button', 'b2b-ecommerce' ),
					'type'     => 'text',
					'desc'     => __( 'This Will Change The Label Of Add to RFQ Button', 'b2b-ecommerce' ),
					'id'       => self::$settings_tab . '_add_to_rfq_button_text',
					'value'    => ( '' !== $add_to_rfq_btn_txt ) ? $add_to_rfq_btn_txt : 'Add To RFQ',
				),

				'accept_rfq_button_text'  => array(
					'name'     => __( 'Accept Quote Button Label', 'b2b-ecommerce' ),
					'desc_tip' => __( 'This Will Change The Label Of Accept Button', 'b2b-ecommerce' ),
					'type'     => 'text',
					'desc'     => __( 'This Will Change The Label Of Accept Quote Button', 'b2b-ecommerce' ),
					'id'       => self::$settings_tab . '_accept_rfq_button_text',
					'value'    => ( '' !== $accept_btn_txt ) ? $accept_btn_txt : 'Accept',
				),

				'reject_rfq_button_text'  => array(
					'name'     => __( 'Reject Quote Button Label', 'b2b-ecommerce' ),
					'desc_tip' => __( 'This Will Change The Label Of Reject Quote Button', 'b2b-ecommerce' ),
					'type'     => 'text',
					'desc'     => __( 'This Will Change The Label Of Reject Quote Button', 'b2b-ecommerce' ),
					'id'       => self::$settings_tab . '_reject_rfq_button_text',
					'value'    => ( '' !== $reject_btn_txt ) ? $reject_btn_txt : 'Reject',
				),

				'revison_rfq_button_text' => array(
					'name'     => __( 'Need Revision Button Label', 'b2b-ecommerce' ),
					'desc_tip' => __( 'This Will Change The Label Of Need Revision Button', 'b2b-ecommerce' ),
					'type'     => 'text',
					'desc'     => __( 'This Will Change The Label Of Need Revision Button', 'b2b-ecommerce' ),
					'id'       => self::$settings_tab . '_revison_rfq_button_text',
					'value'    => ( '' !== $revision_btn_txt ) ? $revision_btn_txt : 'Need Revision',
				),

				'view_rfq_button_text'    => array(
					'name'     => __( 'View Quote Button Label', 'b2b-ecommerce' ),
					'desc_tip' => __( 'This Will Change The Label Of View Quote Button', 'b2b-ecommerce' ),
					'type'     => 'text',
					'desc'     => __( 'This Will Change The Label Of View Quote Button', 'b2b-ecommerce' ),
					'id'       => self::$settings_tab . '_view_rfq_button_text',
					'value'    => ( '' !== $view_btn_txt ) ? $view_btn_txt : 'View Quote',
				),
				'rfq_cart_page'           => array(
					'name'     => __( 'RFQ Cart Page', 'b2b-ecommerce' ),
					'type'     => 'select',
					'id'       => 'b2be_rfq_cart_page',
					'class'    => '',
					'options'  => $all_pages,
					'desc_tip' => __( 'Allows you to set selected page as rfq cart page.', 'b2b-ecommerce' ),
					'desc'     => __( 'Allows you to set selected page as rfq cart page.', 'b2b-ecommerce' ),
				),
				'section_end'             => array(
					'type' => 'sectionend',
					'id'   => self::$settings_tab . '_section_end',
				),
			);

			return apply_filters( 'wc_settings_tab_' . self::$settings_tab, $settings );
		}

		/**
		 * Display 'Enable RFQ' and 'Disable Add to cart' settings on add new product category.
		 */
		public static function add_category_settings() {
			wp_nonce_field( 'rfq_cat_settings', 'rfq_settings_nonce' );
			wp_enqueue_style( 'b2be-rfq-product-category-css' );
			?>
			<tr class="form-field">
				<th scope="row" valign="top" colspan="2">
					<label>
						<p class="rfq-category-disabled-text">
							<?php esc_html_e( 'Purchase ', 'b2b-ecommerce' ); ?>
							<a href="https://woocommerce.com/products/b2b-ecommerce-for-woocommerce/"><?php esc_html_e( 'B2B Ecommerce For WooCommerce Pro ', 'b2b-ecommerce' ); ?></a>
							<?php esc_html_e( 'To Get This Feature.', 'b2b-ecommerce' ); ?>
						</p>
					</label>
			</tr>
			<div class="form-field">
				<label for="b2be_rfq[enable_rfq]">
					<input name="b2be_rfq[enable_rfq]" id="b2be_rfq[enable_rfq]" type="checkbox" class="" value="1" disabled="disabled"><?php esc_html_e( 'Enable RFQ ', 'b2b-ecommerce' ); ?>
				</label>
				<p class="description"><?php esc_html_e( 'This will enable RFQ on products belong to this cateogory.', 'b2b-ecommerce' ); ?></p>
			</div>
			<div class="form-field">
				<label for="b2be_rfq[disable_add_to_cart]">
					<input name="b2be_rfq[disable_add_to_cart]" id="b2be_rfq[disable_add_to_cart]" type="checkbox" class="" value="1" disabled="disabled"><?php esc_html_e( 'Disable add to cart ', 'b2b-ecommerce' ); ?>
				</label>
				<p class="description"><?php esc_html_e( 'This will disable Add to cart on products belong to this cateogory.', 'b2b-ecommerce' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Display 'Enable RFQ' and 'Disable Add to cart' settings on add edit product category.
		 *
		 * @param object $term Term Object.
		 */
		public static function edit_category_settings( $term ) {
			$term_id   = $term->term_id;
			$term_meta = get_term_meta( $term_id, 'taxonomy_setting', true );
			wp_enqueue_style( 'b2be-rfq-product-category-css' );
			?>
			<tr class="form-field">
				<th scope="row" valign="top" colspan="2">
					<label>
						<p class="rfq-category-disabled-text">
							<?php esc_html_e( 'Purchase ', 'b2b-ecommerce' ); ?>
							<a href="https://woocommerce.com/products/b2b-ecommerce-for-woocommerce/"><?php esc_html_e( 'B2B Ecommerce For WooCommerce Pro ', 'b2b-ecommerce' ); ?></a>
							<?php esc_html_e( 'To Get This Feature.', 'b2b-ecommerce' ); ?>
						</p>
					</label>
				</th>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="b2be_rfq[enable_rfq]"><?php esc_html_e( 'RFQ', 'b2b-ecommerce' ); ?></label></th>
				<td>
					<input name="b2be_rfq[enable_rfq]" id="b2be_rfq[enable_rfq]" type="checkbox" class="b2be_rfq_enable_rfq-edit" value="1" disabled="disabled" <?php echo ( isset( $term_meta['enable_rfq'] ) ? 'checked="checked"' : '' ); ?>><span id="add_to_rfq_span"><?php isset( $term_meta['enable_rfq'] ) ? esc_html_e( ' RFQ Is Enabled', 'b2b-ecommerce' ) : esc_html_e( ' RFQ Is Disabled', 'b2b-ecommerce' ); ?></span>
					<p class="edit_cat_rfq_description">
					<?php isset( $term_meta['enable_rfq'] ) ? esc_html_e( 'This will enable RFQ on products belong to this cateogory.', 'b2b-ecommerce' ) : esc_html_e( 'This will disable RFQ on products belong to this cateogory.', 'b2b-ecommerce' ); ?>
					</p>
				</td>
			</tr> 
			<tr class="form-field">
				<th scope="row" valign="top"><label for="b2be_rfq[disable_add_to_cart]"><?php esc_html_e( 'Add to cart ', 'b2b-ecommerce' ); ?></label></th>
				<td>
					<input name="b2be_rfq[disable_add_to_cart]" id="b2be_rfq[disable_add_to_cart]" type="checkbox" class="b2be_rfq_disable_add_to_cart-edit" value="1" disabled="disabled" <?php echo ( isset( $term_meta['disable_add_to_cart'] ) ? 'checked="checked"' : '' ); ?> ><span id="add_to_cart_span"><?php isset( $term_meta['disable_add_to_cart'] ) ? esc_html_e( ' Add To Cart Is Disabled', 'b2b-ecommerce' ) : esc_html_e( ' Add To Cart Is Enabled', 'b2b-ecommerce' ); ?></span>
					<p class="edit_cat_cart_description">
					<?php isset( $term_meta['disable_add_to_cart'] ) ? esc_html_e( 'This will disable Add to cart on products belong to this cateogory.', 'b2b-ecommerce' ) : esc_html_e( 'This will enable Add to cart on products belong to this cateogory.', 'b2b-ecommerce' ); ?>
					</p>
				</td>
			</tr>
			<?php
		}

		/**
		 * Add RFQ tab in edit product screen.
		 *
		 * @param array $tabs Tabs.
		 * @return array
		 */
		public static function rfq_product_settings_tab( $tabs ) {

			$tabs['codup_rfq'] = array(
				'label'  => __( 'RFQ', 'b2b-ecommerce' ),
				'target' => 'b2be_rfq-options',
			);
			return $tabs;
		}

		/**
		 * Show product RFQ settings in RFQ tab.
		 *
		 * @global object $post
		 * @param array $param Parameter.
		 */
		public static function rfq_product_tab_content( $param ) {
			global $post;
			$checked      = '';
			$cart_checked = '';
			wp_nonce_field( 'rfq_product_settings', 'rfq_settings_nonce' );
			wp_enqueue_style( 'b2be-rfq-product-category-css' );
			?>
			<div id='b2be_rfq-options' class='panel woocommerce_options_panel'>
				<div class='options_group'>
					<?php
					if ( 'yes' === get_post_meta( $post->ID, 'enable_rfq', true ) ) {
						$checked = 'yes';
					}
					if ( 'yes' === get_post_meta( $post->ID, 'disable_add_to_cart', true ) ) {
						$cart_checked = 'yes';
					}
					woocommerce_wp_checkbox(
						array(
							'id'          => 'enable_rfq',
							'label'       => __( 'Enable RFQ', 'b2b-ecommerce' ),
							'description' => ( 'yes' == $checked ) ? __( 'Adds a RFQ button for this product.', 'b2b-ecommerce' ) : __( 'Removes a RFQ button for this product.', 'b2b-ecommerce' ),
							'value'       => $checked,
							'custom_attributes' => array(
								'disabled' => 'disabled'
							),
						)
					);

					woocommerce_wp_checkbox(
						array(
							'id'          => 'disable_add_to_cart',
							'label'       => __( 'Disable Add to Cart', 'b2b-ecommerce' ),
							'description' => ( 'yes' == $cart_checked ) ? __( 'Hide  Add to cart on this product.', 'b2b-ecommerce' ) : __( 'Show Add to cart on this product.', 'b2b-ecommerce' ),
							'value'       => $cart_checked,
							'custom_attributes' => array(
								'disabled' => 'disabled'
							),
						)
					);
					?>
				</div>
				<div class="rfq-disabled-overlay">
					<p class="rfq-disabled-overlay-text">
						<?php esc_html_e( 'Purchase ', 'b2b-ecommerce' ); ?>
						<a style="color: gold;" href="https://woocommerce.com/products/b2b-ecommerce-for-woocommerce/"><?php esc_html_e( 'B2B Ecommerce For WooCommerce Pro', 'b2b-ecommerce' ); ?></a>
						<?php esc_html_e( ' To Get This Feature.', 'b2b-ecommerce' ); ?>
					</p>
				</div>
			</div>
			<?php
		}
		
		/**
		 * Check if 'Add to cart' is disabled for category.
		 *
		 * @param string $term_id Term ID.
		 * @return boolean
		 */
		public static function disable_category_add_to_cart( $term_id ) {

			$category_settings = get_term_meta( $term_id, 'taxonomy_setting', true );

			if ( isset( $category_settings['disable_add_to_cart'] ) ) {
				return true;
			}
			return false;
		}
		/**
		 * Check  if 'Add to Cart ' is disabled globally.
		 *
		 * @return boolean
		 */
		public static function disable_add_to_cart() {

			$global_settings = get_option( 'b2be_rfq_disable_add_to_cart', 'no' );
			if ( 'yes' == $global_settings ) {
				return true;
			}
			return false;
		}
		/**
		 * Check if 'Add to RFQ' is enabled on specific category.
		 *
		 * @param string $term_id Term Id.
		 * @return boolean
		 */
		public static function enable_category_add_to_rfq( $term_id ) {
			$category_settings = get_term_meta( $term_id, 'taxonomy_setting', true );
			if ( isset( $category_settings['enable_rfq'] ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Check  if 'Add to RFQ' is enabled globally.
		 *
		 * @return boolean
		 */
		public static function add_to_rfq_enabled() {
			$global_settings = get_option( 'b2be_rfq_enable_rfq', 'no' );
			if ( 'yes' == $global_settings ) {
				return true;
			}
			return false;
		}

		/**
		 * Check  if 'Has Terms' is enabled.
		 *
		 * @return boolean
		 */
		public static function has_terms_enabled() {
			$global_settings = get_option( 'b2be_rfq_enable_has_terms', 'no' );
			if ( 'yes' == $global_settings ) {
				return true;
			}
			return false;
		}
	}
}
