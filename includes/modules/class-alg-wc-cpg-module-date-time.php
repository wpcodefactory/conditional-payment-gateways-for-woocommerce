<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Date Time
 *
 * @version 2.6.0
 * @since   2.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Conditional_Payment_Gateways\Modules
 */

defined( 'ABSPATH' ) || exit;

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
		 * @version 2.3.0
		 * @since   2.0.0
		 */
		function get_default_priority() {
			return 100;
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
			return __( 'Currently "%gateway_title%" is not available.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
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
				sprintf(
					/* translators: %1$s: Date format, %2$s: Hyphen symbol */
					__( 'Options must be set as date range(s) in %1$s format, i.e., dates must be separated with the hyphen %2$s symbol.', 'conditional-payment-gateways-for-woocommerce' ),
					'<code>from-to</code>',
					'<code>-</code>'
				),
				sprintf(
					/* translators: %s: Example list of date ranges */
					__( 'You can add multiple date ranges, one per line (algorithm stops on first matching date range), i.e.: %s', 'conditional-payment-gateways-for-woocommerce' ),
					'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( 'from1-to1', 'from2-to2' ) ) . '</pre>'
				),
				sprintf(
					/* translators: %1$s: PHP strtotime() function page link, %2$s: Hyphen symbol, %3$s: "from", %4$s: "to" */
					__( 'Dates can be set in any format parsed by the PHP %1$s function, except you can\'t use hyphen %2$s symbol, as it\'s reserved for separating %3$s and %4$s values.', 'conditional-payment-gateways-for-woocommerce' ),
					'<a target="_blank" href="https://www.php.net/manual/en/function.strtotime.php"><code>strtotime()</code></a>',
					'<code>-</code>',
					'<code>from</code>',
					'<code>to</code>'
				),
				sprintf(
					/* translators: %s: Current date */
					__( 'Current date: %s', 'conditional-payment-gateways-for-woocommerce' ),
					'<code>' . date( 'Y/m/d H:i:s', current_time( 'timestamp' ) ) . '</code>' // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				),
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
						sprintf(
							/* translators: %s: Example time range */
							__( 'Enable payment gateway only before 3:00 PM each day: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . '00:00:00-14:59:59' . '</pre>'
						),
						sprintf(
							/* translators: %s: Example time range */
							__( 'Enable payment gateway only before 3:00 PM each day, or before 5:00 PM on Mondays: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '00:00:00-14:59:59', 'Monday 00:00:00-Monday 16:59:59' ) ) . '</pre>'
						),
						sprintf(
							/* translators: %s: Example date range */
							__( 'Enable payment gateway for the summer months only: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . 'first day of June - last day of August 23:59:59' . '</pre>'
						),
						sprintf(
							/* translators: %s: Example date range */
							__( 'Enable payment gateway for the February only: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . 'first day of February - last day of February 23:59:59' . '</pre>'
						),
					);
				case 'excl':
					return array(
						sprintf(
							/* translators: %s: Example time range */
							__( 'Disable payment gateway each day after 4:00 PM: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . '16:00:00-23:59:59' . '</pre>'
						),
						sprintf(
							/* translators: %s: Example time range */
							__( 'Disable payment gateway each day after 4:00 PM, and for the whole day on weekends: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '16:00:00-23:59:59', 'Saturday 00:00:00-Sunday 23:59:59' ) ) . '</pre>'
						),
						sprintf(
							/* translators: %s: Example date range */
							__( 'Disable payment gateway for the summer months: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . 'first day of June - last day of August 23:59:59' . '</pre>'
						),
						sprintf(
							/* translators: %s: Example date range */
							__( 'Disable payment gateway for the February: %s', 'conditional-payment-gateways-for-woocommerce' ),
							'<pre' . $this->get_pre_style() . '>' . 'first day of February - last day of February 23:59:59' . '</pre>'
						),
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
				$this->current_value = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			}
			return $this->current_value;
		}

		/**
		 * process.
		 *
		 * @version 2.6.0
		 * @since   2.0.0
		 */
		function process( $value ) {
			$current_value = $this->get_current_value();
			$value         = array_map( 'trim', explode( PHP_EOL, $value ) );
			foreach ( $value as $_value ) {
				$_value = array_map( 'trim', explode( '-', $_value ) );
				if ( 2 === count( $_value ) ) {
					$start_time  = strtotime( $_value[0], $current_value );
					$end_time    = strtotime( $_value[1], $current_value );
					$is_in_range = ( $current_value >= $start_time && $current_value <= $end_time );
					if ( alg_wc_cpg()->core->do_debug ) {
						$_value = sprintf(
							/* translators: %1$s: Start date/time, %2$s: End date/time */
							__( 'from %1$s to %2$s', 'conditional-payment-gateways-for-woocommerce' ),
							date( 'Y/m/d H:i:s', $start_time ), // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
							date( 'Y/m/d H:i:s', $end_time ) // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
						);
						alg_wc_cpg()->core->add_to_log(
							sprintf(
								/* translators: %1$s: Module title, %2$s: Value, %3$s: Current value, %4$s: Result */
								__( '[%1$s] Value: %2$s; Current: %3$s; Result: %4$s;', 'conditional-payment-gateways-for-woocommerce' ),
								$this->get_title(),
								$_value,
								date( 'Y/m/d H:i:s', $current_value ), // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
								( $is_in_range ? 'yes' : 'no' )
							)
						);
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
