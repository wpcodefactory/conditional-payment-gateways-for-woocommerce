<?php
/*
Plugin Name: Conditional Payment Gateways for WooCommerce
Plugin URI: https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/
Description: Manage payment gateways in WooCommerce. Beautifully.
Version: 2.5.1
Author: WPFactory
Author URI: https://wpfactory.com
Requires at least: 4.4
Text Domain: conditional-payment-gateways-for-woocommerce
Domain Path: /langs
WC tested up to: 10.1
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

if ( 'conditional-payment-gateways-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 2.2.0
	 * @since   2.1.0
	 */
	$plugin = 'conditional-payment-gateways-for-woocommerce-pro/conditional-payment-gateways-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		(
			is_multisite() &&
			array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
		)
	) {
		defined( 'ALG_WC_CPG_FILE_FREE' ) || define( 'ALG_WC_CPG_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_CPG_VERSION' ) || define( 'ALG_WC_CPG_VERSION', '2.5.1' );

defined( 'ALG_WC_CPG_FILE' ) || define( 'ALG_WC_CPG_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-cpg.php';

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
