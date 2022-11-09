<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module' ) ) :

abstract class Alg_WC_CPG_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		return true;
	}

	/**
	 * get_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	abstract function get_id();

	/**
	 * get_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	abstract function get_title();

	/**
	 * current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	abstract function get_current_value();

	/**
	 * process.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 */
	function process( $value ) {
		$current_value = $this->get_current_value();
		$value         = ( ! is_array( $value ) ? array_map( 'trim', explode( PHP_EOL, $value ) ) : $value );
		$result        = ( in_array( $current_value, $value ) );
		if ( alg_wc_cpg()->core->do_debug ) {
			alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s] Value: %s; Current: %s; Result: %s;', 'conditional-payment-gateways-for-woocommerce' ),
				$this->get_title(), implode( ',', $value ), $current_value, ( $result ? 'yes' : 'no' ) ) );
		}
		return $result;
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
	 * get_priority.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (feature) add this option to settings
	 */
	function get_priority() {
		return $this->get_option( false, 'priority', false, $this->get_default_priority() );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_desc() {
		return '';
	}

	/**
	 * get_submodules.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_submodules() {
		return array( 'incl', 'excl' );
	}

	/**
	 * get_settings_field_type.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_field_type() {
		return 'textarea';
	}

	/**
	 * get_settings_field_default.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_default() {
		return '';
	}

	/**
	 * get_settings_field_options.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_options() {
		return array();
	}

	/**
	 * get_settings_field_class.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_class() {
		return '';
	}

	/**
	 * get_settings_field_desc.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_desc() {
		return '';
	}

	/**
	 * get_submodule_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_submodule_title( $submodule ) {
		switch ( $submodule ) {
			case 'incl':
				$template = __( 'Require %s', 'conditional-payment-gateways-for-woocommerce' );
				break;
			case 'excl':
				$template = __( 'Exclude %s', 'conditional-payment-gateways-for-woocommerce' );
				break;
			case 'min':
				$template = __( 'Minimum %s', 'conditional-payment-gateways-for-woocommerce' );
				break;
			case 'max':
				$template = __( 'Maximum %s', 'conditional-payment-gateways-for-woocommerce' );
				break;
		}
		return sprintf( $template, $this->get_title() );
	}

	/**
	 * get_submodule_desc.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_submodule_desc( $submodule ) {
		return '';
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_notice( $submodule ) {
		return '';
	}

	/**
	 * get_notice_placeholders.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_notice_placeholders() {
		return array( '%gateway_title%' );
	}

	/**
	 * format_notice_value.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function format_notice_value( $value ) {
		return $value;
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_notes() {
		return array();
	}

	/**
	 * get_shortcode_settings_notes.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (desc) better desc, e.g. add example: `[alg_wc_cpg_if value1="{alg_wc_cpg_cart_total}" value2="1000" operator="less than"]Monday 00:00:00 - Monday 23:59:59[/alg_wc_cpg_if]`
	 */
	function get_shortcode_settings_notes() {
		return array(
			sprintf( __( 'You can use <a target="_blank" href="%s">shortcodes</a> when setting the values.', 'conditional-payment-gateways-for-woocommerce' ),
				'https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/#section-shortcodes' ),
		);
	}

	/**
	 * get_settings_examples.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_examples( $submodule ) {
		return array();
	}

	/**
	 * get_pre_style.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_pre_style() {
		return ' style="color:#3c434a;background-color:#dfdfe0;padding:10px;"';
	}

	/**
	 * get_extra_settings.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_extra_settings() {
		return array();
	}

	/**
	 * get_notice.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (dev) `get_current_value()`: is it safe to use? add it to the settings descriptions? add formatted `%current%` placeholder?
	 * @todo    [later] (dev) better default values?
	 * @todo    [maybe] (dev) shortcodes instead of placeholders, i.e. `[gateway_title]`, `[value]`, `[total]`?
	 */
	function get_notice( $submodule, $gateway, $value, $result ) {
		$notice_template = do_shortcode( $this->get_option( $submodule, 'notice', false, $this->get_default_notice( $submodule ) ) );
		$placeholders    = array(
			'%gateway_title%' => $gateway->title,
			'%value%'         => $this->format_notice_value( $value ),
			'%result%'        => $this->format_notice_value( $result ),
			'%raw_value%'     => $value,
			'%raw_result%'    => $result,
			'%current_raw%'   => $this->get_current_value(),
		);
		return str_replace( array_keys( $placeholders ), $placeholders, $notice_template );
	}

	/**
	 * get_option_name.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    [maybe] (dev) `array_filter`: `false ===` only?
	 */
	function get_option_name( $submodule = false, $suffix = false, $key = false ) {
		return implode( '_', array_filter( array( 'alg_wc_cpg', $this->get_id(), $submodule, $suffix ) ) ) . ( false !== $key ? "[{$key}]" : '' );
	}

	/**
	 * get_option.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_option( $submodule = false, $suffix = false, $key = false, $default = false ) {
		return get_option( $this->get_option_name( $submodule, $suffix, $key ), $default );
	}

}

endif;
