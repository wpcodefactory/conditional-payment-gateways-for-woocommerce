<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - Product Tag
 *
 * @version 2.4.0
 * @since   2.4.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Module_User_Product_Tag' ) ) :

class Alg_WC_CPG_Module_User_Product_Tag extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_id() {
		return 'product_tag';
	}

	/**
	 * process.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function process( $value ) {
		$current_value = $this->get_current_value();
		$result        = ( ! empty( array_intersect( $current_value, $value ) ) );
		if ( alg_wc_cpg()->core->do_debug ) {
			alg_wc_cpg()->core->add_to_log( sprintf( __( '[%s] Value: %s; Current: %s; Result: %s;', 'conditional-payment-gateways-for-woocommerce' ),
				$this->get_title(), implode( ',', $value ), implode( ',', $current_value ), ( $result ? 'yes' : 'no' ) ) );
		}
		return $result;
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_default_priority() {
		return 1000;
	}

	/**
	 * get_title.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_title() {
		return __( 'Product Tag', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_desc() {
		return __( 'Hides payment gateways by cart product tags.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for the cart products.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_shortcode_settings_notes.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
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
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_settings_field_type() {
		return 'multiselect';
	}

	/**
	 * get_settings_field_default.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_settings_field_default() {
		return array();
	}

	/**
	 * get_settings_field_options.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 *
	 * @todo    (dev) add "No tags" option?
	 */
	function get_settings_field_options() {
		$terms = get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => false ) );
		$terms = ( $terms && ! empty( $terms ) && ! is_wp_error( $terms ) ?
			wp_list_pluck( $terms, 'name', 'term_id' ) :
			array()
		);
		return $terms;
	}

	/**
	 * get_settings_field_class.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_settings_field_class() {
		return 'chosen_select';
	}

	/**
	 * get_settings_field_desc.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_settings_field_desc() {
		return
			'<a href="#" class="button alg-wc-cpg-select-all">'   . __( 'Select all', 'conditional-payment-gateways-for-woocommerce' )   . '</a>' . ' ' .
			'<a href="#" class="button alg-wc-cpg-deselect-all">' . __( 'Deselect all', 'conditional-payment-gateways-for-woocommerce' ) . '</a>';
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = array();
			if ( isset( WC()->cart ) ) {
				foreach ( WC()->cart->get_cart() as $item ) {
					$this->current_value = array_merge( $this->current_value, wc_get_product_term_ids( $item['product_id'], 'product_tag' ) );
				}
				$this->current_value = array_unique( $this->current_value );
			}
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_User_Product_Tag();
