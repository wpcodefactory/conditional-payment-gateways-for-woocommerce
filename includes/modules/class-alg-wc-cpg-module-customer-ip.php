<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Customer IP
 *
 * @version 2.3.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

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
	 * process.
	 *
	 * @version 2.2.2
	 * @since   2.2.2
	 */
	function process( $value ) {
		$current_ip = $this->get_current_value();
		$ips        = array_map( 'trim', explode( PHP_EOL, $value ) );
		$result     = ( false === strpos( $value, '/' ) ?
			in_array( $current_ip, $ips ) :
			$this->process_cidr( $current_ip, $ips )
		);
		if ( alg_wc_cpg()->core->do_debug ) {
			alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s] Value: %s; Current: %s; Result: %s;', 'conditional-payment-gateways-for-woocommerce' ),
				$this->get_title(), implode( ',', $ips ), $current_ip, ( $result ? 'yes' : 'no' ) ) );
		}
		return $result;
	}

	/**
	 * process_cidr.
	 *
	 * @version 2.2.2
	 * @since   2.2.2
	 *
	 * @see     https://gist.github.com/jonavon/2028872
	 */
	function process_cidr( $current_ip, $ips ) {
		if ( ! class_exists( 'Alg_WC_CPG_CIDR' ) ) {
			require_once( alg_wc_cpg()->plugin_path() . '/assets/cidr/CIDR.php' );
		}
		$result = false;
		foreach ( $ips as $ip ) {
			$ip_parts = count( explode( '/', $ip ) );
			if ( 1 == $ip_parts ) {
				if ( $current_ip == $ip ) {
					$result = true;
					break;
				}
			} elseif ( 2 == $ip_parts ) {
				if ( Alg_WC_CPG_CIDR::IPisWithinCIDR( $current_ip, $ip ) ) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	function get_default_priority() {
		return 200;
	}

	/**
	 * get_desc.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (desc) better desc?
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
		return __( '"%gateway_title%" is not available for your IP address.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 *
	 * @todo    (desc) CIDR: add link to https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing
	 */
	function get_settings_notes() {
		return array(
			sprintf( __( 'Options must be set as a list of IPs, one IP per line, accepts CIDR ranges, e.g.: %s', 'conditional-payment-gateways-for-woocommerce' ),
				'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '127.0.0.1', '172.16.0.9', '192.0.0.7', '10.0.0.0/8' ) ) . '</pre>' ),
		);
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 *
	 * @todo    (feature) wildcards, etc.
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
