<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Product Title
 *
 * @version 2.6.1
 * @since   2.3.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Conditional_Payment_Gateways\Modules
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Module_Product_Title' ) ) :

	/**
	 * Alg_WC_CPG_Module_Product_Title class.
	 *
	 * @version 2.6.1
	 * @since   2.3.0
	 */
	class Alg_WC_CPG_Module_Product_Title extends Alg_WC_CPG_Module {

		/**
		 * Get ID.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function get_id() {
			return 'product_title';
		}

		/**
		 * Process.
		 *
		 * @version 2.6.1
		 * @since   2.3.0
		 *
		 * @param string $value The value.
		 *
		 * @return bool
		 */
		public function process( $value ) {
			$current_value = $this->get_current_value();
			$value         = array_map( 'trim', explode( PHP_EOL, $value ) );
			$func          = $this->get_option( false, 'func', false, 'strpos' );
			$result        = false;
			foreach ( $value as $_value ) {
				foreach ( $current_value as $_current_value ) {
					if (
						in_array( $func, array( 'strpos', 'stripos' ), true ) &&
						false !== $func( $_current_value, $_value )
					) {
						$result = true;
						break;
					}
				}
				if ( $result ) {
					break;
				}
			}
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
		 * Get default priority.
		 *
		 * @version 2.4.0
		 * @since   2.3.0
		 */
		public function get_default_priority() {
			return 1300;
		}

		/**
		 * Get title.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function get_title() {
			return __( 'Product Title', 'conditional-payment-gateways-for-woocommerce' );
		}

		/**
		 * Get description.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function get_desc() {
			return __( 'Hides payment gateways by cart product titles (or descriptions).', 'conditional-payment-gateways-for-woocommerce' );
		}

		/**
		 * Get default notice.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 *
		 * @param string $submodule The submodule.
		 *
		 * @return string
		 */
		public function get_default_notice( $submodule ) {
			return __( '"%gateway_title%" is not available for the cart products.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		}

		/**
		 * Get settings notes.
		 *
		 * @version 2.6.0
		 * @since   2.3.0
		 */
		public function get_settings_notes() {
			return array(
				sprintf(
					/* translators: %s: Example list of titles */
					__( 'Options must be set as a list of titles, one title per line, e.g.: %s', 'conditional-payment-gateways-for-woocommerce' ),
					'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( 'sony', 'playstation' ) ) . '</pre>'
				),
			);
		}

		/**
		 * Get extra settings.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function get_extra_settings() {
			return array(
				array(
					'title' => __( 'Settings', 'conditional-payment-gateways-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_product_title_extra_options',
				),
				array(
					'title'   => __( 'Product data', 'conditional-payment-gateways-for-woocommerce' ),
					'id'      => 'alg_wc_cpg_product_title_source',
					'default' => array( 'get_title' ),
					'type'    => 'multiselect',
					'class'   => 'chosen_select',
					'options' => array(
						'get_title'             => __( 'Product title', 'conditional-payment-gateways-for-woocommerce' ),
						'get_description'       => __( 'Product description', 'conditional-payment-gateways-for-woocommerce' ),
						'get_short_description' => __( 'Product short description', 'conditional-payment-gateways-for-woocommerce' ),
					),
				),
				array(
					'title'   => __( 'Comparison function', 'conditional-payment-gateways-for-woocommerce' ),
					'id'      => 'alg_wc_cpg_product_title_func',
					'default' => 'strpos',
					'type'    => 'select',
					'class'   => 'chosen_select',
					'options' => array(
						'strpos'  => __( 'Case-sensitive', 'conditional-payment-gateways-for-woocommerce' ),
						'stripos' => __( 'Case-insensitive', 'conditional-payment-gateways-for-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_product_title_extra_options',
				),
			);
		}

		/**
		 * Get current value.
		 *
		 * @version 2.6.1
		 * @since   2.3.0
		 */
		public function get_current_value() {
			if ( ! isset( $this->current_value ) ) {
				$this->current_value = array();
				if ( isset( WC()->cart ) ) {
					$data_source = $this->get_option( false, 'source', false, array( 'get_title' ) );
					foreach ( WC()->cart->get_cart() as $item ) {
						$product = $item['data'];
						if ( $product ) {
							foreach ( $data_source as $source ) {
								if (
									in_array( $source, array( 'get_title', 'get_description', 'get_short_description' ), true ) &&
									is_callable( array( $product, $source ) )
								) {
									$this->current_value[] = $product->{$source}();
								}
							}
						}
					}
				}
			}
			return $this->current_value;
		}
	}

endif;

return new Alg_WC_CPG_Module_Product_Title();
