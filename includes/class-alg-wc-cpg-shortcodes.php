<?php
/**
 * Conditional Payment Gateways for WooCommerce - Shortcodes Class
 *
 * @version 2.0.1
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Shortcodes' ) ) :

class Alg_WC_CPG_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		add_shortcode( 'alg_wc_cpg_if',          array( $this, 'shortcode_if' ) );
		add_shortcode( 'alg_wc_cpg_cart_total',  array( $this, 'shortcode_cart_total' ) );
		add_shortcode( 'alg_wc_cpg_user_id',     array( $this, 'shortcode_user_id' ) );
		add_shortcode( 'alg_wc_cpg_translate',   array( $this, 'shortcode_language' ) );
	}

	/**
	 * shortcode_user_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function shortcode_user_id( $atts, $content = '' ) {
		return get_current_user_id();
	}

	/**
	 * shortcode_cart_total.
	 *
	 * @version 2.0.1
	 * @since   2.0.0
	 *
	 * @todo    [next] (feature) incl/excl products (including variations), product categories/tags
	 * @todo    [next] (feature) subtotal
	 * @todo    [next] (feature) fees
	 */
	function shortcode_cart_total( $atts, $content = '' ) {
		if ( ! function_exists( 'WC' ) || ! isset( WC()->cart ) ) {
			return 0;
		}
		// Default atts
		$default_atts = array(
			'exclude_taxes'     => 'yes',
			'exclude_shipping'  => 'no',
			'exclude_discounts' => 'no',
		);
		$atts = shortcode_atts( $default_atts, $atts, 'alg_wc_cpg_cart_total' );
		// Prepare atts
		$do_exclude_taxes     = filter_var( $atts['exclude_taxes'],     FILTER_VALIDATE_BOOLEAN );
		$do_exclude_shipping  = filter_var( $atts['exclude_shipping'],  FILTER_VALIDATE_BOOLEAN );
		$do_exclude_discounts = filter_var( $atts['exclude_discounts'], FILTER_VALIDATE_BOOLEAN );
		// Cart total
		remove_filter( 'woocommerce_available_payment_gateways', array( alg_wc_cpg()->core, 'available_payment_gateways' ), PHP_INT_MAX );
		WC()->cart->calculate_totals();
		add_filter(    'woocommerce_available_payment_gateways', array( alg_wc_cpg()->core, 'available_payment_gateways' ), PHP_INT_MAX );
		$cart_total = WC()->cart->get_total( 'edit' );
		if ( $do_exclude_taxes ) {
			$cart_total -= WC()->cart->get_total_tax();
		}
		if ( $do_exclude_shipping ) {
			$cart_total -= ( WC()->cart->get_shipping_total() + ( ! $do_exclude_taxes ? WC()->cart->get_shipping_tax() : 0 ) );
		}
		if ( $do_exclude_discounts ) {
			$cart_total += ( WC()->cart->get_discount_total() + ( ! $do_exclude_taxes ? WC()->cart->get_discount_tax() : 0 ) );
		}
		return floatval( $cart_total );
	}

	/**
	 * shortcode_if.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (dev) `shortcode_atts`?
	 * @todo    [next] (feature) more `values`: e.g. `user_role`, `user_ip`, `current_time`, `product_id_in_cart`, `product_cat_in_cart`, etc.
	 * @todo    [next] (feature) more `operators`: e.g. `in`
	 * @todo    [maybe] (dev) code refactoring
	 */
	function shortcode_if( $atts, $content = '' ) {
		if ( ! isset( $atts['value1'] ) || ! isset( $atts['value2'] ) || ! isset( $atts['operator'] ) ) {
			return '';
		}
		// Prepare value atts
		$atts['value1'] = do_shortcode( $this->process_att( $atts['value1'] ) );
		$atts['value2'] = do_shortcode( $this->process_att( $atts['value2'] ) );
		// Logic
		$result = false;
		switch ( $atts['operator'] ) {
			case 'equal':
				$result = (   alg_wc_cpg()->core->is_equal_float( $atts['value1'], $atts['value2'] ) );
				break;
			case 'greater than':
			case 'greater_than':
				$result = ( ! alg_wc_cpg()->core->is_equal_float( $atts['value1'], $atts['value2'] ) && $atts['value1'] > $atts['value2'] );
				break;
			case 'less than':
			case 'less_than':
				$result = ( ! alg_wc_cpg()->core->is_equal_float( $atts['value1'], $atts['value2'] ) && $atts['value1'] < $atts['value2'] );
				break;
			case 'greater than or equal':
			case 'greater_than_or_equal':
				$result = (   alg_wc_cpg()->core->is_equal_float( $atts['value1'], $atts['value2'] ) || $atts['value1'] > $atts['value2'] );
				break;
			case 'less than or equal':
			case 'less_than_or_equal':
				$result = (   alg_wc_cpg()->core->is_equal_float( $atts['value1'], $atts['value2'] ) || $atts['value1'] < $atts['value2'] );
				break;
			case 'not equal':
			case 'not_equal':
				$result = ( ! alg_wc_cpg()->core->is_equal_float( $atts['value1'], $atts['value2'] ) );
				break;
			case 'between':
			case 'greater than and less than':
			case 'greater_than_and_less_than':
				$value2 = array_map( 'trim', explode( ',', $atts['value2'] ) );
				if ( 2 == count( $value2 ) ) {
					$result = (
						( ! alg_wc_cpg()->core->is_equal_float( $atts['value1'], $value2[0] ) && $atts['value1'] > $value2[0] ) &&
						( ! alg_wc_cpg()->core->is_equal_float( $atts['value1'], $value2[1] ) && $atts['value1'] < $value2[1] )
					);
				}
				break;
			case 'between or equal':
			case 'between_or_equal':
			case 'greater than or equal and less than or equal':
			case 'greater_than_or_equal_and_less_than_or_equal':
				$value2 = array_map( 'trim', explode( ',', $atts['value2'] ) );
				if ( 2 == count( $value2 ) ) {
					$result = (
						(   alg_wc_cpg()->core->is_equal_float( $atts['value1'], $value2[0] ) || $atts['value1'] > $value2[0] ) &&
						(   alg_wc_cpg()->core->is_equal_float( $atts['value1'], $value2[1] ) || $atts['value1'] < $value2[1] )
					);
				}
				break;
		}
		// Final output
		if ( $result ) {
			$then = $content;
			if ( '' === $then && isset( $atts['then'] ) ) {
				$then = $this->process_att( $atts['then'] );
			}
			return do_shortcode( $then );
		} else {
			$else = '';
			if ( isset( $atts['else'] ) ) {
				$else = $this->process_att( $atts['else'] );
			}
			return do_shortcode( $else );
		}
	}

	/**
	 * shortcode_language.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function shortcode_language( $atts, $content = '' ) {
		// E.g.: `[alg_wc_cpg_translate lang="EN,DE" lang_text="EN & DE text" not_lang_text="Text for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_cpg_translate lang="EN,DE"]EN & DE text[/alg_wc_cpg_translate][alg_wc_cpg_translate not_lang="EN,DE"]Text for other languages[/alg_wc_cpg_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * process_att.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function process_att( $att ) {
		return str_replace( array( '{', '}' ), array( '[', ']' ), $att );
	}

}

endif;

return new Alg_WC_CPG_Shortcodes();
