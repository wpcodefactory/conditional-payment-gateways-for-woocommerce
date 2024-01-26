<?php
/**
 * Conditional Payment Gateways for WooCommerce - Core Class
 *
 * @version 2.2.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Core' ) ) :

class Alg_WC_CPG_Core {

	/**
	 * modules.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $modules;

	/**
	 * do_debug.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $do_debug;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (feature) optional "After checkout validation" (only or both)
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_cpg_plugin_enabled', 'yes' ) ) {
			$this->do_debug = ( 'yes' === get_option( 'alg_wc_cpg_debug_enabled', 'no' ) );
			require_once( 'class-alg-wc-cpg-shortcodes.php' );
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), PHP_INT_MAX );
		}
		// Core loaded
		do_action( 'alg_wc_cpg_core_loaded' );
	}

	/**
	 * get_modules.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 */
	function get_modules() {
		if ( ! isset( $this->modules ) ) {
			require_once( 'modules/classes/class-alg-wc-cpg-module.php' );
			$this->modules = array(
				require_once( 'modules/class-alg-wc-cpg-module-date-time.php' ),
				require_once( 'modules/class-alg-wc-cpg-module-customer-ip.php' ),
				require_once( 'modules/class-alg-wc-cpg-module-user.php' ),
				require_once( 'modules/class-alg-wc-cpg-module-cart-total.php' ),
				require_once( 'modules/class-alg-wc-cpg-module-currency.php' ),
				require_once( 'modules/class-alg-wc-cpg-module-language.php' ),
			);
			uasort( $this->modules, array( $this, 'sort_modules_by_priority' ) );
		}
		return $this->modules;
	}

	/**
	 * sort_modules_by_priority.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function sort_modules_by_priority( $a, $b ) {
		if ( $a->get_priority() == $b->get_priority() ) {
			return 0;
		}
		return ( $a->get_priority() < $b->get_priority() ) ? -1 : 1;
	}

	/**
	 * is_equal_float.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function is_equal_float( $float1, $float2 ) {
		return ( abs( $float1 - $float2 ) < ( defined( 'PHP_FLOAT_EPSILON' ) ? PHP_FLOAT_EPSILON : 0.000000001 ) );
	}

	/**
	 * add_to_log.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function add_to_log( $message ) {
		if ( function_exists( 'wc_get_logger' ) && ( $log = wc_get_logger() ) ) {
			$log->log( 'info', $message, array( 'source' => 'conditional-payment-gateways-for-woocommerce' ) );
		}
	}

	/**
	 * available_payment_gateways.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 *
	 * @todo    [maybe] (dev) notices: `wc_clear_notices()`?
	 * @todo    [maybe] (dev) notices: `wp_doing_ajax()`?
	 */
	function available_payment_gateways( $available_gateways ) {
		$notices = array();
		// Check gateways
		foreach ( $this->get_modules() as $module ) {
			foreach ( $module->get_submodules() as $submodule ) {
				if ( 'yes' === $module->get_option( $submodule, 'enabled', false, 'no' ) ) {
					$values = $module->get_option( $submodule, false, false, array() );
					if ( ! empty( $values ) ) {
						foreach ( $available_gateways as $key => $gateway ) {
							if ( ! apply_filters( 'alg_wc_cpg_pre_check', ( in_array( $key, array( 'cheque', 'bacs', 'cod', 'paypal' ) ) ) ) ) {
								continue;
							}
							$value = ( ! empty( $values[ $key ] ) ? ( is_array( $values[ $key ] ) ? $values[ $key ] : do_shortcode( $values[ $key ] ) ) : false );
							if ( ! empty( $value ) ) {
								$result = $module->process( $value );
								switch ( $submodule ) {
									case 'incl':
										$is_active =   $result;
										break;
									case 'excl':
										$is_active = ! $result;
										break;
									case 'min':
										$is_active = $this->is_equal_float( $result, $value ) || $result > $value;
										break;
									case 'max':
										$is_active = $this->is_equal_float( $result, $value ) || $result < $value;
										break;
								}
								if ( ! $is_active ) {
									if ( 'no' === get_option( 'alg_wc_cpg_leave_at_least_one_gateway', 'no' ) || count( $available_gateways ) > 1 ) {
										if ( alg_wc_cpg()->core->do_debug ) {
											alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s > %s] Disabling: %s;', 'conditional-payment-gateways-for-woocommerce' ),
												$module->get_title(), $submodule, $key ) );
										}
										unset( $available_gateways[ $key ] );
										if ( 'yes' === $module->get_option( $submodule, 'notice_enabled', false, 'yes' ) ) {
											if ( '' !== ( $notice = $module->get_notice( $submodule, $gateway, ( is_array( $value ) ? implode( ', ', $value ) : $value ), $result ) ) ) {
												$notices[] = $notice;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		// Add notices
		if ( ! empty( $notices ) && is_checkout() && wp_doing_ajax() ) {
			$notice_type = get_option( 'alg_wc_cpg_notice_type', 'notice' );
			foreach ( $notices as $notice ) {
				if ( ! wc_has_notice( $notice, $notice_type ) ) {
					wc_add_notice( $notice, $notice_type );
				}
			}
		}
		return $available_gateways;
	}

}

endif;

return new Alg_WC_CPG_Core();
