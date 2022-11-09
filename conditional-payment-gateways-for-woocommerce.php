<?php
/*
Plugin Name: Conditional Payment Gateways for WooCommerce
Plugin URI: https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/
Description: Manage payment gateways in WooCommerce. Beautifully.
Version: 2.1.0
Author: Algoritmika Ltd
Author URI: https://algoritmika.com
Text Domain: conditional-payment-gateways-for-woocommerce
Domain Path: /langs
WC tested up to: 6.0
*/

defined( 'ABSPATH' ) || exit;

if ( 'conditional-payment-gateways-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	$plugin = 'conditional-payment-gateways-for-woocommerce-pro/conditional-payment-gateways-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

defined( 'ALG_WC_CPG_VERSION' ) || define( 'ALG_WC_CPG_VERSION', '2.1.0' );

defined( 'ALG_WC_CPG_FILE' )    || define( 'ALG_WC_CPG_FILE',    __FILE__ );

require_once( 'includes/class-alg-wc-cpg.php' );

if ( ! function_exists( 'alg_wc_cpg' ) ) {
	/**
	 * Returns the main instance of Alg_WC_CPG to prevent the need to use globals.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function alg_wc_cpg() {
		return Alg_WC_CPG::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_cpg' );
