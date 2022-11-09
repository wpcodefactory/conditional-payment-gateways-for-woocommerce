<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Customer IP
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_Customer_IP' ) ) :

class Alg_WC_CPG_Module_Customer_IP extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_id() {
		return 'ip';
	}

	/**
	 * get_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_title() {
		return __( 'Customer IP', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_priority() {
		return 20;
	}

	/**
	 * get_desc.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (desc) better desc?
	 */
	function get_desc() {
		return __( 'Hides payment gateways by current customer IP.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for your IP address.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_notes() {
		return array(
			sprintf( __( 'Options must be set as list of IPs, one IP per line, e.g.: %s', 'conditional-payment-gateways-for-woocommerce' ),
				'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '127.0.0.1', '172.16.0.9', '192.0.0.7' ) ) . '</pre>' ),
		);
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 *
	 * @todo    [next] (feature) ranges and/or wildcards
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = WC_Geolocation::get_ip_address();
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_Customer_IP();
