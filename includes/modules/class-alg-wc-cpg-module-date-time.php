<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Date Time
 *
 * @version 2.2.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_Date_Time' ) ) :

class Alg_WC_CPG_Module_Date_Time extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_id() {
		return 'date_time';
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_priority() {
		return 10;
	}

	/**
	 * get_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_title() {
		return __( 'Date/Time', 'conditional-payment-gateways-for-woocommerce' );
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
		return __( 'Hides payment gateways by current date and time.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_notice( $submodule ) {
		return __( 'Currently "%gateway_title%" is not available.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.2.0
	 * @since   2.0.0
	 *
	 * @todo    (dev) fix: hyphen is not allowed
	 */
	function get_settings_notes() {
		return array(
			sprintf( __( 'Options must be set as date range(s) in %s format, i.e., dates must be separated with the hyphen %s symbol.', 'conditional-payment-gateways-for-woocommerce' ),
				'<code>from-to</code>', '<code>-</code>' ),
			sprintf( __( 'You can add multiple date ranges, one per line (algorithm stops on first matching date range), i.e.: %s', 'conditional-payment-gateways-for-woocommerce' ),
				'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( 'from1-to1', 'from2-to2' ) ) . '</pre>' ),
			sprintf( __( 'Dates can be set in any format parsed by the PHP %s function, except you can\'t use hyphen %s symbol, as it\'s reserved for separating %s and %s values.', 'conditional-payment-gateways-for-woocommerce' ),
				'<a target="_blank" href="https://www.php.net/manual/en/function.strtotime.php"><code>strtotime()</code></a>', '<code>-</code>', '<code>from</code>', '<code>to</code>' ),
			sprintf( __( 'Current date: %s', 'conditional-payment-gateways-for-woocommerce' ),
				'<code>' . date( 'Y/m/d H:i:s', current_time( 'timestamp' ) ) . '</code>' ),
		);
	}

	/**
	 * get_settings_examples.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_examples( $submodule ) {
		switch ( $submodule ) {
			case 'incl':
				return array(
					sprintf( __( 'Enable payment gateway only before 3:00 PM each day: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . '00:00:00-14:59:59' . '</pre>' ),
					sprintf( __( 'Enable payment gateway only before 3:00 PM each day, or before 5:00 PM on Mondays: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '00:00:00-14:59:59', 'Monday 00:00:00-Monday 16:59:59' ) ) . '</pre>' ),
					sprintf( __( 'Enable payment gateway for the summer months only: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . 'first day of June - last day of August 23:59:59' . '</pre>' ),
					sprintf( __( 'Enable payment gateway for the February only: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . 'first day of February - last day of February 23:59:59' . '</pre>' ),
				);
			case 'excl':
				return array(
					sprintf( __( 'Disable payment gateway each day after 4:00 PM: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . '16:00:00-23:59:59' . '</pre>' ),
					sprintf( __( 'Disable payment gateway each day after 4:00 PM, and for the whole day on weekends: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '16:00:00-23:59:59', 'Saturday 00:00:00-Sunday 23:59:59' ) ) . '</pre>' ),
					sprintf( __( 'Disable payment gateway for the summer months: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . 'first day of June - last day of August 23:59:59' . '</pre>' ),
					sprintf( __( 'Disable payment gateway for the February: %s', 'conditional-payment-gateways-for-woocommerce' ),
						'<pre' . $this->get_pre_style() . '>' . 'first day of February - last day of February 23:59:59' . '</pre>' ),
				);
		}
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = current_time( 'timestamp' );
		}
		return $this->current_value;
	}

	/**
	 * process.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 */
	function process( $value ) {
		$current_value = $this->get_current_value();
		$value         = array_map( 'trim', explode( PHP_EOL, $value ) );
		foreach ( $value as $_value ) {
			$_value = array_map( 'trim', explode( '-', $_value ) );
			if ( 2 == count( $_value ) ) {
				$start_time  = strtotime( $_value[0], $current_value );
				$end_time    = strtotime( $_value[1], $current_value );
				$is_in_range = ( $current_value >= $start_time && $current_value <= $end_time );
				if ( alg_wc_cpg()->core->do_debug ) {
					$_value = sprintf( __( 'from %s to %s', 'conditional-payment-gateways-for-woocommerce' ), date( 'Y/m/d H:i:s', $start_time ), date( 'Y/m/d H:i:s', $end_time ) );
					alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s] Value: %s; Current: %s; Result: %s;', 'conditional-payment-gateways-for-woocommerce' ),
						$this->get_title(), $_value, date( 'Y/m/d H:i:s', $current_value ), ( $is_in_range ? 'yes' : 'no' ) ) );
				}
				if ( $is_in_range ) {
					return true;
				}
			}
		}
		return false;
	}

}

endif;

return new Alg_WC_CPG_Module_Date_Time();
