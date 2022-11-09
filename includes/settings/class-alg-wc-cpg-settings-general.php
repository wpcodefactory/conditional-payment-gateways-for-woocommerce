<?php
/**
 * Conditional Payment Gateways for WooCommerce - General Section Settings
 *
 * @version 2.1.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CPG_Settings_General' ) ) :

class Alg_WC_CPG_Settings_General extends Alg_WC_CPG_Settings_Section {

	/**
	 * add_admin_style.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function add_admin_style() {
		$ids = array();
		foreach ( alg_wc_cpg()->core->get_modules() as $module ) {
			foreach ( $module->get_submodules() as $submodule ) {
				$ids[] = '.form-table td fieldset label[for=' . $module->get_option_name( $submodule, 'enabled', false ) .']';
			}
		}
		echo '<style> ' . implode( ', ', $ids ) . ' { margin-top: 0 !important; margin-bottom: 0 !important; line-height: 1 !important; } </style>';
	}

	/**
	 * get_settings.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 *
	 * @todo    [next] (desc) `alg_wc_cpg_leave_at_least_one_gateway`: better desc?
	 */
	function get_settings() {

		add_action( 'admin_footer', array( $this, 'add_admin_style' ), PHP_INT_MAX );

		$plugin_settings = array(
			array(
				'title'    => __( 'Conditional Payment Gateways Options', 'conditional-payment-gateways-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cpg_plugin_options',
			),
			array(
				'title'    => __( 'Conditional Payment Gateways', 'conditional-payment-gateways-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'conditional-payment-gateways-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_cpg_plugin_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Debug', 'conditional-payment-gateways-for-woocommerce' ),
				'desc'     => __( 'Enable', 'conditional-payment-gateways-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Will add a log to %s.', 'conditional-payment-gateways-for-woocommerce' ),
					'<a href="' . admin_url( 'admin.php?page=wc-status&tab=logs' ) . '">' . __( 'WooCommerce > Status > Logs', 'conditional-payment-gateways-for-woocommerce' ) . '</a>' ),
				'id'       => 'alg_wc_cpg_debug_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Leave at least one gateway', 'conditional-payment-gateways-for-woocommerce' ),
				'desc'     => __( 'Enable', 'conditional-payment-gateways-for-woocommerce' ),
				'desc_tip' => __( 'Will ensure that when all payment gateways do not match "enabled" criteria, one last remaining gateway will still remain active in any case.', 'conditional-payment-gateways-for-woocommerce' ),
				'id'       => 'alg_wc_cpg_leave_at_least_one_gateway',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Notice styling', 'conditional-payment-gateways-for-woocommerce' ),
				'desc_tip' => __( 'Will be used for all additional (optional) notices.', 'conditional-payment-gateways-for-woocommerce' ),
				'id'       => 'alg_wc_cpg_notice_type',
				'default'  => 'notice',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'notice'  => __( 'Notice', 'conditional-payment-gateways-for-woocommerce' ),
					'error'   => __( 'Error', 'conditional-payment-gateways-for-woocommerce' ),
					'success' => __( 'Success', 'conditional-payment-gateways-for-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cpg_plugin_options',
			),
		);

		$modules_settings = array(
			array(
				'title'    => __( 'Available Conditions', 'conditional-payment-gateways-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cpg_modules',
			),
		);
		$modules         = alg_wc_cpg()->core->get_modules();
		$modules_total   = count( $modules );
		$modules_counter = 0;
		foreach ( $modules as $module ) {
			$modules_counter++;
			$module_link        = admin_url( 'admin.php?page=wc-settings&tab=alg_wc_cpg&section=' . $module->get_id() );
			$module_title       = esc_html( strip_tags( $module->get_title() . ': ' . $module->get_desc() ) );
			$submodules         = $module->get_submodules();
			$submodules_total   = count( $submodules );
			$submodules_counter = 0;
			foreach ( $submodules as $submodule ) {
				$submodules_counter++;
				$modules_settings = array_merge( $modules_settings, array(
					array(
						'title'         => ( 1 === $modules_counter && 1 === $submodules_counter ? __( 'Conditions', 'conditional-payment-gateways-for-woocommerce' ) : '' ),
						'desc'          => '<a title="' . $module_title . '" href="' . $module_link . '">' . $module->get_submodule_title( $submodule ) . '</a>',
						'type'          => 'checkbox',
						'id'            => $module->get_option_name( $submodule, 'enabled', false ),
						'default'       => 'no',
						'checkboxgroup' => ( 1 === $modules_counter && 1 === $submodules_counter ? 'start' : ( $modules_total === $modules_counter && $submodules_total === $submodules_counter ? 'end' : '' ) ),
					),
				) );
			}
		}
		$modules_settings = array_merge( $modules_settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cpg_modules',
			),
		) );

		return array_merge( $plugin_settings, $modules_settings );
	}

}

endif;

return new Alg_WC_CPG_Settings_General( '', __( 'General', 'conditional-payment-gateways-for-woocommerce' ) );
