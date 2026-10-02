<?php
/**
 * Conditional Payment Gateways for WooCommerce - Settings
 *
 * @version 2.6.0
 * @since   2.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Conditional_Payment_Gateways\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Settings' ) ) :

	/**
	 * Alg_WC_CPG_Settings class.
	 *
	 * @version 2.6.0
	 * @since   2.0.0
	 */
	class Alg_WC_CPG_Settings extends WC_Settings_Page {

		/**
		 * Constructor.
		 *
		 * @version 2.6.0
		 * @since   2.0.0
		 */
		public function __construct() {
			$this->id    = 'alg_wc_cpg';
			$this->label = __( 'Conditional Payment Gateways', 'conditional-payment-gateways-for-woocommerce' );
			parent::__construct();

			// Sections.
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-cpg-settings-section.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-cpg-settings-general.php';
			foreach ( alg_wc_cpg()->core->get_modules() as $module ) {
				new Alg_WC_CPG_Settings_Section( $module->get_id(), $module->get_title(), $module );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @version 2.6.0
		 * @since   2.1.0
		 *
		 * @param string $hook The current admin page hook.
		 */
		public function enqueue_admin_scripts( $hook ) {
			if (
				'woocommerce_page_wc-settings' !== $hook ||
				! isset( $_GET['tab'] ) || // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'alg_wc_cpg' !== sanitize_text_field( wp_unslash( $_GET['tab'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			) {
				return;
			}

			// Styles.
			$ids = array();
			foreach ( alg_wc_cpg()->core->get_modules() as $module ) {
				foreach ( $module->get_submodules() as $submodule ) {
					$ids[] = '.form-table td fieldset label[for=' . $module->get_option_name( $submodule, 'enabled', false ) . ']';
				}
			}

			if ( ! empty( $ids ) ) {
				$style = (
					implode( ', ', $ids ) . ' {' .
						' margin-top: 0 !important;' .
						' margin-bottom: 0 !important;' .
						' line-height: 1 !important;' .
					' }'
				);

				wp_register_style(
					'alg-wc-cpg-admin-settings',
					false,
					array(),
					alg_wc_cpg()->version
				);

				wp_enqueue_style( 'alg-wc-cpg-admin-settings' );

				wp_add_inline_style(
					'alg-wc-cpg-admin-settings',
					wp_strip_all_tags( $style )
				);
			}

			// Scripts.
			wp_register_script(
				'alg-wc-cpg-admin-settings',
				false,
				array( 'jquery' ),
				alg_wc_cpg()->version,
				true
			);

			wp_enqueue_script( 'alg-wc-cpg-admin-settings' );

			wp_add_inline_script(
				'alg-wc-cpg-admin-settings',
				"jQuery( document ).ready( function () {
					jQuery( '.alg-wc-cpg-select-all' ).click( function ( event ) {
						event.preventDefault();
						jQuery( this ).closest( 'td' ).find( 'select.chosen_select' ).select2( 'destroy' ).find( 'option' ).prop( 'selected', 'selected' ).end().select2();
						return false;
					} );
					jQuery( '.alg-wc-cpg-deselect-all' ).click( function ( event ) {
						event.preventDefault();
						jQuery( this ).closest( 'td' ).find( 'select.chosen_select' ).val( '' ).change();
						return false;
					} );
				} );"
			);
		}

		/**
		 * Get settings.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_settings() {
			global $current_section;
			return array_merge(
				apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				array(
					array(
						'title' => __( 'Reset Settings', 'conditional-payment-gateways-for-woocommerce' ),
						'type'  => 'title',
						'id'    => $this->id . '_' . $current_section . '_reset_options',
					),
					array(
						'title'    => __( 'Reset section settings', 'conditional-payment-gateways-for-woocommerce' ),
						'desc'     => '<strong>' . __( 'Reset', 'conditional-payment-gateways-for-woocommerce' ) . '</strong>',
						'desc_tip' => __( 'Check the box and save changes to reset.', 'conditional-payment-gateways-for-woocommerce' ),
						'id'       => $this->id . '_' . $current_section . '_reset',
						'default'  => 'no',
						'type'     => 'checkbox',
					),
					array(
						'type' => 'sectionend',
						'id'   => $this->id . '_' . $current_section . '_reset_options',
					),
				)
			);
		}

		/**
		 * Maybe reset settings.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function maybe_reset_settings() {
			global $current_section;
			if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
				foreach ( $this->get_settings() as $value ) {
					if ( isset( $value['id'] ) ) {
						$id = explode( '[', $value['id'] );
						delete_option( $id[0] );
					}
				}
				if ( method_exists( 'WC_Admin_Settings', 'add_message' ) ) {
					WC_Admin_Settings::add_message(
						__( 'Your settings have been reset.', 'conditional-payment-gateways-for-woocommerce' )
					);
				} else {
					add_action(
						'admin_notices',
						array( $this, 'admin_notices_settings_reset_success' )
					);
				}
			}
		}

		/**
		 * Admin notices settings reset success.
		 *
		 * @version 2.5.0
		 * @since   2.0.0
		 */
		public function admin_notices_settings_reset_success() {
			echo '<div class="notice notice-success is-dismissible"><p><strong>' .
				esc_html__( 'Your settings have been reset.', 'conditional-payment-gateways-for-woocommerce' ) .
			'</strong></p></div>';
		}

		/**
		 * Save.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function save() {
			parent::save();
			$this->maybe_reset_settings();
		}
	}

endif;

return new Alg_WC_CPG_Settings();
