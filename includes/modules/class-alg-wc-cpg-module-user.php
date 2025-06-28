<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module - User
 *
 * @version 2.3.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Module_User' ) ) :

class Alg_WC_CPG_Module_User extends Alg_WC_CPG_Module {

	/**
	 * get_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_id() {
		return 'user';
	}

	/**
	 * get_default_priority.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	function get_default_priority() {
		return 300;
	}

	/**
	 * get_title.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_title() {
		return __( 'User', 'conditional-payment-gateways-for-woocommerce' );
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
		return __( 'Hides payment gateways by current user ID.', 'conditional-payment-gateways-for-woocommerce' );
	}

	/**
	 * get_default_notice.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_default_notice( $submodule ) {
		return __( '"%gateway_title%" is not available for the current user.', 'conditional-payment-gateways-for-woocommerce' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	}

	/**
	 * get_settings_notes.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	function get_settings_notes() {
		return array(
			sprintf(
				/* Translators: %s: Example. */
				__( 'Options must be set as a list of user IDs, one per line, e.g.: %s', 'conditional-payment-gateways-for-woocommerce' ),
				'<pre' . $this->get_pre_style() . '>' . implode( PHP_EOL, array( '100', '101', '122' ) ) . '</pre>'
			),
			sprintf(
				/* Translators: %s: Zero. */
				__( 'For guests, i.e., not logged in users, use %s (zero).', 'conditional-payment-gateways-for-woocommerce' ),
				'<code>0</code>'
			),
		);
	}

	/**
	 * get_current_value.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 *
	 * @todo    (feature) user *email*
	 * @todo    (feature) ranges and/or wildcards
	 */
	function get_current_value() {
		if ( ! isset( $this->current_value ) ) {
			$this->current_value = get_current_user_id();
		}
		return $this->current_value;
	}

}

endif;

return new Alg_WC_CPG_Module_User();
