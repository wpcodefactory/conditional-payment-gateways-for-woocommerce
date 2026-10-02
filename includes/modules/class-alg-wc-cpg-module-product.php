<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Product
 *
 * @version 2.6.0
 * @since   2.4.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Conditional_Payment_Gateways\Modules
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Module_Product' ) ) :

	class Alg_WC_CPG_Module_Product extends Alg_WC_CPG_Module {

		/**
		 * Constructor.
		 *
		 * @version 2.6.0
		 * @since   2.4.0
		 */
		function __construct() {
			add_filter( 'alg_wc_cpg_gateway_settings_product', array( $this, 'settings_field' ), 10, 3 );
		}

		/**
		 * get_id.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_id() {
			return 'product';
		}

		/**
		 * process.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function process( $value ) {
			$current_value = $this->get_current_value();
			$result        = ( ! empty( array_intersect( $current_value, $value ) ) );
			if ( alg_wc_cpg()->core->do_debug ) {
				alg_wc_cpg()->core->add_to_log(
					sprintf(
						/* translators: %1$s: Module title, %2$s: Value, %3$s: Current value, %4$s: Result */
						__( '[%1$s] Value: %2$s; Current: %3$s; Result: %4$s;', 'conditional-payment-gateways-for-woocommerce' ),
						$this->get_title(),
						implode( ',', $value ),
						implode( ',', $current_value ),
						( $result ? 'yes' : 'no' )
					)
				);
			}
			return $result;
		}

		/**
		 * get_default_priority.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_default_priority() {
			return 800;
		}

		/**
		 * get_title.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_title() {
			return __( 'Product', 'conditional-payment-gateways-for-woocommerce' );
		}

		/**
		 * get_desc.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_desc() {
			return __( 'Hides payment gateways by cart products.', 'conditional-payment-gateways-for-woocommerce' );
		}

		/**
		 * get_default_notice.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_default_notice( $submodule ) {
			return __( '"%gateway_title%" is not available for the cart products.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		}

		/**
		 * get_shortcode_settings_notes.
		 *
		 * @version 2.6.0
		 * @since   2.4.0
		 */
		function get_shortcode_settings_notes() {
			return array(
				__( 'You can use shortcodes when setting the "Additional notice" values.', 'conditional-payment-gateways-for-woocommerce' ),
			);
		}

		/**
		 * get_settings_field_type.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_settings_field_type() {
			return 'multiselect';
		}

		/**
		 * get_settings_field_default.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_settings_field_default() {
			return array();
		}

		/**
		 * settings_field.
		 *
		 * @version 2.6.0
		 * @since   2.4.0
		 */
		function settings_field( $settings, $submodule, $key ) {

			$settings['options'] = array();
			$product_ids         = $this->get_option( $submodule, false, false, array() );
			if ( ! empty( $product_ids[ $key ] ) ) {
				foreach ( $product_ids[ $key ] as $product_id ) {
					$product_label = (
						( $product = wc_get_product( $product_id ) ) ?
						wp_strip_all_tags( $product->get_formatted_name() ) :
						sprintf(
							/* translators: %d: Product ID */
							__( 'Product #%d', 'conditional-payment-gateways-for-woocommerce' ),
							$product_id
						)
					);
					$settings['options'][ $product_id ] = esc_html( $product_label );
				}
			}

			if ( empty( $settings['custom_attributes'] ) ) {
				$settings['custom_attributes'] = array();
			}
			$settings['custom_attributes'] = array_merge(
				$settings['custom_attributes'],
				array(
					'data-placeholder' => esc_attr__( 'Search for a product&hellip;', 'conditional-payment-gateways-for-woocommerce' ),
					'data-allow_clear' => 'true',
					'data-action'      => 'woocommerce_json_search_products_and_variations',
				)
			);

			return $settings;
		}

		/**
		 * get_settings_field_class.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_settings_field_class() {
			return 'wc-product-search';
		}

		/**
		 * get_current_value.
		 *
		 * @version 2.4.0
		 * @since   2.4.0
		 */
		function get_current_value() {
			if ( ! isset( $this->current_value ) ) {
				$this->current_value = array();
				if ( isset( WC()->cart ) ) {
					foreach ( WC()->cart->get_cart() as $item ) {
						$this->current_value[] = $item['product_id'];
						$this->current_value[] = $item['variation_id'];
					}
					$this->current_value = array_unique( $this->current_value );
				}
			}
			return $this->current_value;
		}
	}

endif;

return new Alg_WC_CPG_Module_Product();
