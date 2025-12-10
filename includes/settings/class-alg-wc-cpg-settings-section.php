<?php
/**
 * Conditional Payment Gateways for WooCommerce - Section Settings
 *
 * @version 2.5.2
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Settings_Section' ) ) :

class Alg_WC_CPG_Settings_Section {

	/**
	 * id.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $id;

	/**
	 * desc.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $desc;

	/**
	 * module.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $module;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct( $id, $desc, $module = false ) {

		$this->id     = $id;
		$this->desc   = $desc;
		$this->module = $module;

		add_filter(
			'woocommerce_get_sections_alg_wc_cpg',
			array( $this, 'settings_section' )
		);
		add_filter(
			'woocommerce_get_settings_alg_wc_cpg_' . $this->id,
			array( $this, 'get_settings' ),
			PHP_INT_MAX
		);

	}

	/**
	 * settings_section.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * add_admin_script.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 *
	 * @todo    (dev) move this to a separate js file
	 * @todo    (dev) load on needed pages only
	 */
	function add_admin_script() {
		?><script>
			jQuery( document ).ready( function () {
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
			} );
		</script><?php
	}

	/**
	 * get_payment_gateway_full_title.
	 *
	 * @version 2.5.2
	 * @since   2.5.2
	 */
	function get_payment_gateway_full_title( $gateway, $key ) {

		// Get title
		$full_title = $gateway->get_title();

		// Maybe add admin title
		$admin_title = $gateway->get_method_title();
		if (
			'' !== $admin_title &&
			$full_title !== $admin_title
		) {
			$full_title .= " ({$admin_title})";
		}

		// Fallback
		if ( '' === $full_title ) {
			$full_title = $key;
		}

		return $full_title;

	}

	/**
	 * get_settings.
	 *
	 * @version 2.5.2
	 * @since   2.0.0
	 *
	 * @todo    (dev) `notice`: `alg_wc_cpg_raw`?
	 */
	function get_settings() {

		add_action( 'admin_footer', array( $this, 'add_admin_script' ) );

		$settings = array(
			array(
				'title'    => $this->module->get_title(),
				'desc'     => $this->module->get_desc(),
				'type'     => 'title',
				'id'       => $this->module->get_option_name( false, 'options' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => $this->module->get_option_name( false, 'options' ),
			),
		);

		foreach ( $this->module->get_submodules() as $submodule ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => $this->module->get_submodule_title( $submodule ),
					'desc'     => $this->module->get_submodule_desc( $submodule ),
					'type'     => 'title',
					'id'       => $this->module->get_option_name( $submodule, 'options' ),
				),
				array(
					'title'    => __( 'Enable/disable', 'conditional-payment-gateways-for-woocommerce' ),
					'desc'     => '<strong>' . __( 'Enable section', 'conditional-payment-gateways-for-woocommerce' ) . '</strong>',
					'type'     => 'checkbox',
					'id'       => $this->module->get_option_name( $submodule, 'enabled' ),
					'default'  => 'no',
				),
			) );
			foreach ( WC()->payment_gateways->payment_gateways() as $key => $gateway ) {
				$gateway_settings = array(
					'title'             => $this->get_payment_gateway_full_title( $gateway, $key ),
					'desc_tip'          => sprintf(
						/* Translators: %s: Key. */
						__( 'Payment gateway key: %s', 'conditional-payment-gateways-for-woocommerce' ),
						$key
					),
					'type'              => $this->module->get_settings_field_type(),
					'id'                => $this->module->get_option_name( $submodule, false, $key ),
					'default'           => $this->module->get_settings_field_default(),
					'options'           => $this->module->get_settings_field_options(),
					'class'             => $this->module->get_settings_field_class(),
					'css'               => (
						'width:100%;' .
						(
							'textarea' === $this->module->get_settings_field_type() ?
							'height:100px;' :
							''
						)
					),
					'custom_attributes' => apply_filters(
						'alg_wc_cpg_settings',
						(
							! in_array( $key, array( 'cheque', 'bacs', 'cod', 'paypal' ) ) ?
							(
								in_array( $this->module->get_settings_field_type(), array( 'textarea', 'text' ) ) ?
								array( 'readonly' => 'readonly' ) :
								array( 'disabled' => 'disabled' )
							) :
							''
						)
					),
					'desc'              => (
						$this->module->get_settings_field_desc() .
						apply_filters(
							'alg_wc_cpg_settings',
							(
								! in_array( $key, array( 'cheque', 'bacs', 'cod', 'paypal' ) ) ?
								'<p>' .
									sprintf(
										'You will need %s plugin to set conditions for this payment gateway.',
										'<a href="https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/" target="_blank">' .
											'Conditional Payment Gateways for WooCommerce Pro' .
										'</a>'
									) .
								'</p>' :
								''
							)
						)
					),
				);
				$gateway_settings = apply_filters(
					'alg_wc_cpg_gateway_settings_' . $this->module->get_id(),
					$gateway_settings,
					$submodule,
					$key,
					$gateway
				);
				$settings[] = $gateway_settings;
			}
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Additional notice', 'conditional-payment-gateways-for-woocommerce' ),
					'desc'     => __( 'Enable', 'conditional-payment-gateways-for-woocommerce' ),
					'desc_tip' => __( 'In addition to hiding a gateway, you can also add extra notice on the checkout page.', 'conditional-payment-gateways-for-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => $this->module->get_option_name( $submodule, 'notice_enabled' ),
					'default'  => 'yes',
				),
				array(
					'desc'     => sprintf(
						/* Translators: %s: Placeholder list. */
						__( 'Available placeholder(s): %s.', 'conditional-payment-gateways-for-woocommerce' ),
						'<code>' . implode( '</code>, <code>', $this->module->get_notice_placeholders() ) . '</code>'
					),
					'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'conditional-payment-gateways-for-woocommerce' ),
					'type'     => 'textarea',
					'id'       => $this->module->get_option_name( $submodule, 'notice' ),
					'default'  => $this->module->get_default_notice( $submodule ),
					'css'      => 'width:100%;',
				),
				array(
					'type'     => 'sectionend',
					'id'       => $this->module->get_option_name( $submodule, 'options' ),
				),
			) );
		}

		$notes = array_merge( $this->module->get_settings_notes(), $this->module->get_shortcode_settings_notes() );
		if ( ! empty( $notes ) ) {
			$note_icon    = '<span class="dashicons dashicons-info"></span> ';
			$example_icon = '<span class="dashicons dashicons-lightbulb"></span> ';
			$notes        = '<p>' . $note_icon . implode( '</p><p>' . $note_icon, $notes ) . '</p>';
			$notes_title  = __( 'Notes', 'conditional-payment-gateways-for-woocommerce' );
			foreach ( $this->module->get_submodules() as $submodule ) {
				$examples = $this->module->get_settings_examples( $submodule );
				if ( ! empty( $examples ) ) {
					$notes_title  = __( 'Notes & Examples', 'conditional-payment-gateways-for-woocommerce' );
					$notes .= '<details style="margin-bottom: 10px;">' .
						'<summary style="cursor: pointer;">' .
							sprintf(
								/* Translators: %s: Examples. */
								__( 'Examples: %s', 'conditional-payment-gateways-for-woocommerce' ),
								$this->module->get_submodule_title( $submodule )
							) .
						'</summary>' .
						'<p>' . $example_icon . implode( '</p><p>' . $example_icon, $examples ) . '</p>' .
					'</details>';
				}
			}
			$notes = array(
				array(
					'title'    => $notes_title,
					'type'     => 'title',
					'id'       => $this->module->get_option_name( false, 'notes' ),
					'desc'     => '<div style="margin-bottom: 20px;">' . $notes . '</div>',
				),
				array(
					'type'     => 'sectionend',
					'id'       => $this->module->get_option_name( false, 'notes' ),
				),
			);
		}

		return array_merge( $settings, $this->module->get_extra_settings(), $notes );

	}

}

endif;
