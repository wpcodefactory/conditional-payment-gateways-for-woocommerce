<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Language
 *
 * @version 2.1.0
 * @since   2.1.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_Language' ) ) :

class Alg_WC_CPG_Module_Language extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_id() {
		return 'language';
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_default_priority() {
		return 60;
	}

	/**
	 * get_title.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_title() {
		return __( 'Language', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 *
	 * @todo    [next] (dev) WPML/Polylang active/disabled: as an admin notice?
	 */
	function get_desc() {
		$desc_is_wpml_or_polylang_active = ( function_exists( 'icl_get_languages' ) ?
			__( 'WPML or Polylang plugin is active.', 'conditional-payment-gateways-for-woocommerce' ) :
			__( 'WPML or Polylang plugin is disabled.', 'conditional-payment-gateways-for-woocommerce' ) );
		return sprintf( __( 'Hides payment gateways by the current %s or %s language.', 'conditional-payment-gateways-for-woocommerce' ),
			'<a href="https://wpml.org/" target="_blank" title="' . $desc_is_wpml_or_polylang_active . '">WPML</a>',
			'<a href="https://wordpress.org/plugins/polylang/" target="_blank" title="' . $desc_is_wpml_or_polylang_active . '">Polylang</a>' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for the current language.', 'conditional-payment-gateways-for-woocommerce' );
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
		return ( function_exists( 'icl_get_languages' ) ? wp_list_pluck( icl_get_languages(), 'native_name' ) : array() );
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
	 *
	 * @todo    [later] (dev) rethink `LANG_NA`?
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = ( defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : 'LANG_NA' );
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_Language();
