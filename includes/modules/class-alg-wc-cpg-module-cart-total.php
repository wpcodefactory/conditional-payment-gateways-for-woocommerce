<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Cart Total
 *
 * @version 2.2.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_Cart_Total' ) ) :

class Alg_WC_CPG_Module_Cart_Total extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_id() {
		return 'cart_total';
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_priority() {
		return 40;
	}

	/**
	 * get_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_title() {
		return __( 'Cart Total', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.2.0
	 * @since   2.0.0
	 *
	 * @todo    (desc) better desc?
	 */
	function get_desc() {
		return __( 'Hides payment gateways based on min/max cart (i.e., order) amounts.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_submodule_desc.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (desc) better desc?
	 */
	function get_submodule_desc( $submodule ) {
		switch ( $submodule ) {
			case 'min':
				return __( 'Payment gateway will be hidden if cart total is <strong>below</strong> this amount.', 'conditional-payment-gateways-for-woocommerce' );
			case 'max':
				return __( 'Payment gateway will be hidden if cart total <strong>exceeds</strong> this amount.', 'conditional-payment-gateways-for-woocommerce' );
		}
	}

	/**
	 * get_settings_field_type.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings_field_type() {
		return 'text';
	}

	/**
	 * get_submodules.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_submodules() {
		return array( 'min', 'max' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_notice( $submodule ) {
		switch ( $submodule ) {
			case 'min':
				return __( 'Minimum amount for "%gateway_title%" is %value%. Your cart total is %result%.', 'conditional-payment-gateways-for-woocommerce' );
			case 'max':
				return __( 'Maximum amount for "%gateway_title%" is %value%. Your cart total is %result%.', 'conditional-payment-gateways-for-woocommerce' );
		}
	}

	/**
	 * get_notice_placeholders.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (feature) add: `%diff_amount%`
	 * @todo    (dev) aliases: `%cart_total%`, `%amount%`?
	 * @todo    (dev) check: `wc_has_notice()` shouldn't work with `%result%`? if so, then maybe this can be solved with `wc_clear_notices()`?
	 */
	function get_notice_placeholders() {
		return array( '%gateway_title%', '%value%', '%result%' );
	}

	/**
	 * format_notice_value.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function format_notice_value( $value ) {
		return wc_price( $value );
	}

	/**
	 * get_extra_settings.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (desc) `alg_wc_cpg_cart_total_calc`: add link to shortcode description on wpfactory.com
	 * @todo    (dev) `alg_wc_cpg_cart_total_calc`: better default value?
	 */
	function get_extra_settings() {
		return array(
			array(
				'title'    => __( 'Cart Total Calculation', 'conditional-payment-gateways-for-woocommerce' ),
				'desc'     => __( 'This section sets how cart total should be calculated when comparing it with min/max amounts.', 'conditional-payment-gateways-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cpg_cart_total_calc_options',
			),
			array(
				'title'    => __( 'Cart total', 'conditional-payment-gateways-for-woocommerce' ),
				'desc'     => sprintf( __( 'You should use %s shortcode here.', 'conditional-payment-gateways-for-woocommerce' ),
					'<code>[alg_wc_cpg_cart_total]</code>' ),
				'id'       => 'alg_wc_cpg_cart_total_calc',
				'default'  => '[alg_wc_cpg_cart_total exclude_taxes="yes" exclude_shipping="no" exclude_discounts="no"]',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cpg_cart_total_calc_options',
			),
		);
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @todo    (desc) better desc
	 * @todo    (dev) fix: use dot symbol
	 */
	function get_settings_notes() {
		return array(
			__( 'If set to zero - option is ignored.', 'conditional-payment-gateways-for-woocommerce' ),
			sprintf( __( 'For decimal values use dot %s symbol.', 'conditional-payment-gateways-for-woocommerce' ), '<code>.</code>' ),
			sprintf( __( 'In "%s": %s will output current cart total, and %s will output current gateway min or max amount.', 'conditional-payment-gateways-for-woocommerce' ),
				__( 'Additional notice', 'conditional-payment-gateways-for-woocommerce' ), '<code>%result%</code>', '<code>%value%</code>' ),
		);
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = do_shortcode( $this->get_option( false, 'calc', false,
				'[alg_wc_cpg_cart_total exclude_taxes="yes" exclude_shipping="no" exclude_discounts="no"]' ) );
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
		if ( alg_wc_cpg()->core->do_debug ) {
			alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s] Value: %s; Current: %s;', 'conditional-payment-gateways-for-woocommerce' ),
				$this->get_title(), $value, $current_value ) );
		}
		return $current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_Cart_Total();
