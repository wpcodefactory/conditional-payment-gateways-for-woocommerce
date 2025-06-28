<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - User Role
 *
 * @version 2.3.0
 * @since   2.3.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Module_User_Role' ) ) :

class Alg_WC_CPG_Module_User_Role extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_id() {
		return 'user_role';
	}

	/**
	 * process.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
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
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_default_priority() {
		return 400;
	}

	/**
	 * get_title.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_title() {
		return __( 'User Role', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_desc.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_desc() {
		return __( 'Hides payment gateways by current user role.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for the current user.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
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
	 */
	function get_settings_field_options() {
		global $wp_roles;
		return wp_list_pluck( apply_filters( 'editable_roles', $wp_roles->roles ), 'name' );
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
	 * get_current_value.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			if ( function_exists( 'wp_get_current_user' ) ) {
				$current_user = wp_get_current_user();
				$this->current_value = $current_user->roles;
			}
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_User_Role();
