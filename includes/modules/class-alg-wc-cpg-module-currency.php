<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Currency
 *
 * @version 2.3.0
 * @since   2.1.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Module_Currency' ) ) :

class Alg_WC_CPG_Module_Currency extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_id() {
		return 'currency';
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.3.0
	 * @since   2.1.0
	 */
	function get_default_priority() {
		return 600;
	}

	/**
	 * get_title.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_title() {
		return __( 'Currency', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 *
	 * @todo    (desc) better desc?
	 */
	function get_desc() {
		return __( 'Hides payment gateways by the current currency. For example, this is useful, if you are using some additional currency switcher plugin.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for the current currency.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	}

	/**
	 * get_shortcode_settings_notes.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_shortcode_settings_notes() {
		return array(
			sprintf( __( 'You can use <a target="_blank" href="%s">shortcodes</a> when setting the "Additional notice" values.', 'conditional-payment-gateways-for-woocommerce' ),
				'https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/#section-shortcodes' ),
		);
	}

	/**
	 * get_settings_field_type.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_type() {
		return 'multiselect';
	}

	/**
	 * get_settings_field_default.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_default() {
		return array();
	}

	/**
	 * get_settings_field_options.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_options() {
		return get_woocommerce_currencies();
	}

	/**
	 * get_settings_field_class.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_class() {
		return 'chosen_select';
	}

	/**
	 * get_settings_field_desc.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_settings_field_desc() {
		return
			'<a href="#" class="button alg-wc-cpg-select-all">'   . __( 'Select all', 'conditional-payment-gateways-for-woocommerce' )   . '</a>' . ' ' .
			'<a href="#" class="button alg-wc-cpg-deselect-all">' . __( 'Deselect all', 'conditional-payment-gateways-for-woocommerce' ) . '</a>';
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = get_woocommerce_currency();
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_Currency();
