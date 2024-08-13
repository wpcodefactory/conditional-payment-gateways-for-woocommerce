<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Country
 *
 * @version 2.3.0
 * @since   2.3.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_Country' ) ) :

class Alg_WC_CPG_Module_Country extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_id() {
		return 'country';
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_default_priority() {
		return 700;
	}

	/**
	 * get_title.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_title() {
		return __( 'Country', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_desc() {
		return __( 'Hides payment gateways by current user country (by IP, billing or shipping country).', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for your country.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_shortcode_settings_notes.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
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
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_settings_field_type() {
		return 'multiselect';
	}

	/**
	 * get_settings_field_default.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_settings_field_default() {
		return array();
	}

	/**
	 * get_settings_field_options.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 *
	 * @todo    (dev) add "EU" as a separate "country"
	 */
	function get_settings_field_options() {
		return WC()->countries->get_countries();
	}

	/**
	 * get_settings_field_class.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_settings_field_class() {
		return 'chosen_select';
	}

	/**
	 * get_settings_field_desc.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_settings_field_desc() {
		return
			'<a href="#" class="button alg-wc-cpg-select-all">'   . __( 'Select all', 'conditional-payment-gateways-for-woocommerce' )   . '</a>' . ' ' .
			'<a href="#" class="button alg-wc-cpg-deselect-all">' . __( 'Deselect all', 'conditional-payment-gateways-for-woocommerce' ) . '</a>';
	}

	/**
	 * get_extra_settings.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_extra_settings() {
		return array(
			array(
				'title'    => __( 'Country Detection', 'conditional-payment-gateways-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cpg_country_source_options',
			),
			array(
				'title'    => __( 'Country detection', 'conditional-payment-gateways-for-woocommerce' ),
				'id'       => 'alg_wc_cpg_country_source',
				'default'  => 'ip',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'ip'       => __( 'Country by IP (geolocation)', 'conditional-payment-gateways-for-woocommerce' ),
					'billing'  => __( 'Billing country', 'conditional-payment-gateways-for-woocommerce' ),
					'shipping' => __( 'Shipping country', 'conditional-payment-gateways-for-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cpg_country_source_options',
			),
		);
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$country_source = $this->get_option( false, 'source', false, 'ip' );
			switch ( $country_source ) {

				case 'ip':
					if ( class_exists( 'WC_Geolocation' ) ) {
						$geolocation = WC_Geolocation::geolocate_ip( '', true, false );
						if ( ! empty( $geolocation['country'] ) ) {
							$this->current_value = $geolocation['country'];
						}
					}
					break;

				case 'billing':
					if ( isset( WC()->customer ) ) {
						$this->current_value = WC()->customer->get_billing_country();
					}
					break;

				case 'shipping':
					if ( isset( WC()->customer ) ) {
						$this->current_value = WC()->customer->get_shipping_country();
					}
					break;

			}
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_Country();
