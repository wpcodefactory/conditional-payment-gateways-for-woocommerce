<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Product Title
 *
 * @version 2.4.0
 * @since   2.3.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_User_Product_Title' ) ) :

class Alg_WC_CPG_Module_User_Product_Title extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_id() {
		return 'product_title';
	}

	/**
	 * process.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function process( $value ) {
		$current_value = $this->get_current_value();
		$value         = array_map( 'trim', explode( PHP_EOL, $value ) );
		$func          = $this->get_option( false, 'func', false, 'strpos' );
		$result        = false;
		foreach ( $value as $_value ) {
			foreach ( $current_value as $_current_value ) {
				if ( false !== $func( $_current_value, $_value ) ) {
					$result = true;
					break;
				}
			}
			if ( $result ) {
				break;
			}
		}
		if ( alg_wc_cpg()->core->do_debug ) {
			alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s] Value: %s; Current: %s; Result: %s;', 'conditional-payment-gateways-for-woocommerce' ),
				$this->get_title(), implode( ',', $value ), implode( ',', $current_value ), ( $result ? 'yes' : 'no' ) ) );
		}
		return $result;
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.4.0
	 * @since   2.3.0
	 */
	function get_default_priority() {
		return 1300;
	}

	/**
	 * get_title.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_title() {
		return __( 'Product Title', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_desc() {
		return __( 'Hides payment gateways by cart product titles (or descriptions).', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for the cart products.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_settings_notes() {
		return array(
			sprintf( __( 'Options must be set as a list of strings, one string per line, e.g.: %s', 'conditional-payment-gateways-for-woocommerce' ),
				'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( 'sony', 'playstation' ) ) . '</pre>' ),
		);
	}

	/**
	 * get_extra_settings.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_extra_settings() {
		return array(
			array(
				'title'    => __( 'Settings', 'conditional-payment-gateways-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cpg_product_title_extra_options',
			),
			array(
				'title'    => __( 'Product data', 'conditional-payment-gateways-for-woocommerce' ),
				'id'       => 'alg_wc_cpg_product_title_source',
				'default'  => array( 'get_title' ),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => array(
					'get_title'             => __( 'Product title', 'conditional-payment-gateways-for-woocommerce' ),
					'get_description'       => __( 'Product description', 'conditional-payment-gateways-for-woocommerce' ),
					'get_short_description' => __( 'Product short description', 'conditional-payment-gateways-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Comparison function', 'conditional-payment-gateways-for-woocommerce' ),
				'id'       => 'alg_wc_cpg_product_title_func',
				'default'  => 'strpos',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'strpos'  => __( 'Case-sensitive', 'conditional-payment-gateways-for-woocommerce' ),
					'stripos' => __( 'Case-insensitive', 'conditional-payment-gateways-for-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cpg_product_title_extra_options',
			),
		);
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = array();
			if ( isset( WC()->cart ) ) {
				$data_source = $this->get_option( false, 'source', false, array( 'get_title' ) );
				foreach ( WC()->cart->get_cart() as $item ) {
					if ( ( $product = $item['data'] ) ) {
						foreach ( $data_source as $source ) {
							$this->current_value[] = $product->{$source}();
						}
					}
				}
			}
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_User_Product_Title();
