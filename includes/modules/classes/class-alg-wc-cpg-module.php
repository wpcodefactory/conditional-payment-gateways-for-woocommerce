<?php
/**
 * Conditional Payment Gateways for WooCommerce - Module
 *
 * @version 2.6.0
 * @since   2.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Conditional_Payment_Gateways\Modules
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_CPG_Module' ) ) :

	/**
	 * Alg_WC_CPG_Module class.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	abstract class Alg_WC_CPG_Module {

		/**
		 * Current value.
		 *
		 * @version 2.2.0
		 * @since   2.2.0
		 *
		 * @var mixed
		 */
		public $current_value;

		/**
		 * Get ID.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		abstract public function get_id();

		/**
		 * Get title.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		abstract public function get_title();

		/**
		 * Get current value.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 */
		abstract public function get_current_value();

		/**
		 * Process.
		 *
		 * @version 2.1.0
		 * @since   2.0.0
		 */
		public function process( $value ) {
			$current_value = $this->get_current_value();
			$value         = ( ! is_array( $value ) ? array_map( 'trim', explode( PHP_EOL, $value ) ) : $value );
			$result        = ( in_array( $current_value, $value ) );
			if ( alg_wc_cpg()->core->do_debug ) {
				alg_wc_cpg()->core->add_to_log(
					sprintf(
						/* translators: %1$s: Module title, %2$s: Value, %3$s: Current value, %4$s: Result */
						__( '[%1$s] Value: %2$s; Current: %3$s; Result: %4$s;', 'conditional-payment-gateways-for-woocommerce' ),
						$this->get_title(),
						implode( ',', $value ),
						$current_value,
						( $result ? 'yes' : 'no' )
					)
				);
			}
			return $result;
		}

		/**
		 * get_default_priority.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_default_priority() {
			return 10;
		}

		/**
		 * get_priority.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @todo    (feature) add this option to settings
		 */
		public function get_priority() {
			return $this->get_option( false, 'priority', false, $this->get_default_priority() );
		}

		/**
		 * get_desc.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_desc() {
			return '';
		}

		/**
		 * get_submodules.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_submodules() {
			return array( 'incl', 'excl' );
		}

		/**
		 * get_settings_field_type.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_settings_field_type() {
			return 'textarea';
		}

		/**
		 * get_settings_field_default.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 */
		public function get_settings_field_default() {
			return '';
		}

		/**
		 * get_settings_field_options.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 */
		public function get_settings_field_options() {
			return array();
		}

		/**
		 * get_settings_field_class.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 */
		public function get_settings_field_class() {
			return '';
		}

		/**
		 * get_settings_field_desc.
		 *
		 * @version 2.1.0
		 * @since   2.1.0
		 */
		public function get_settings_field_desc() {
			return '';
		}

		/**
		 * get_submodule_title.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_submodule_title( $submodule ) {
			switch ( $submodule ) {
				case 'incl':
					/* translators: %s: Submodule title */
					$template = __( 'Require %s', 'conditional-payment-gateways-for-woocommerce' );
					break;
				case 'excl':
					/* translators: %s: Submodule title */
					$template = __( 'Exclude %s', 'conditional-payment-gateways-for-woocommerce' );
					break;
				case 'min':
					/* translators: %s: Submodule title */
					$template = __( 'Minimum %s', 'conditional-payment-gateways-for-woocommerce' );
					break;
				case 'max':
					/* translators: %s: Submodule title */
					$template = __( 'Maximum %s', 'conditional-payment-gateways-for-woocommerce' );
					break;
			}
			return sprintf( $template, $this->get_title() );
		}

		/**
		 * get_submodule_desc.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_submodule_desc( $submodule ) {
			return '';
		}

		/**
		 * get_default_notice.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_default_notice( $submodule ) {
			return '';
		}

		/**
		 * get_notice_placeholders.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_notice_placeholders() {
			return array( '%gateway_title%' );
		}

		/**
		 * format_notice_value.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function format_notice_value( $value ) {
			return $value;
		}

		/**
		 * get_settings_notes.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_settings_notes() {
			return array();
		}

		/**
		 * get_shortcode_settings_notes.
		 *
		 * @version 2.6.0
		 * @since   2.0.0
		 *
		 * @todo    (desc) Better desc, e.g., add example: `[alg_wc_cpg_if value1="{alg_wc_cpg_cart_total}" value2="1000" operator="less than"]Monday 00:00:00 - Monday 23:59:59[/alg_wc_cpg_if]`.
		 */
		public function get_shortcode_settings_notes() {
			return array(
				__( 'You can use shortcodes when setting the values.', 'conditional-payment-gateways-for-woocommerce' ),
			);
		}

		/**
		 * get_settings_examples.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_settings_examples( $submodule ) {
			return array();
		}

		/**
		 * get_pre_style.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_pre_style() {
			return ' style="color:#3c434a;background-color:#dfdfe0;padding:10px;"';
		}

		/**
		 * get_extra_settings.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_extra_settings() {
			return array();
		}

		/**
		 * get_notice.
		 *
		 * @version 2.3.0
		 * @since   2.0.0
		 *
		 * @todo    (dev) `get_current_value()`: add it to the settings descriptions? add formatted `%current%` placeholder?
		 * @todo    (dev) better default values?
		 * @todo    (dev) shortcodes instead of placeholders, i.e., `[gateway_title]`, `[value]`, `[total]`?
		 */
		public function get_notice( $submodule, $gateway, $value, $result ) {
			$notice_template = do_shortcode( $this->get_option( $submodule, 'notice', false, $this->get_default_notice( $submodule ) ) );
			$current_value   = $this->get_current_value();
			$placeholders    = array(
				'%gateway_title%' => $gateway->title,
				'%value%'         => $this->format_notice_value( $value ),
				'%result%'        => $this->format_notice_value( $result ),
				'%raw_value%'     => $value,
				'%raw_result%'    => $result,
				'%current_raw%'   => ( is_array( $current_value ) ? implode( ', ', $current_value ) : $current_value ),
			);
			return str_replace( array_keys( $placeholders ), $placeholders, $notice_template );
		}

		/**
		 * get_option_name.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @todo    (dev) `array_filter`: `false ===` only?
		 */
		public function get_option_name( $submodule = false, $suffix = false, $key = false ) {
			return implode( '_', array_filter( array( 'alg_wc_cpg', $this->get_id(), $submodule, $suffix ) ) ) . ( false !== $key ? "[{$key}]" : '' );
		}

		/**
		 * get_option.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function get_option( $submodule = false, $suffix = false, $key = false, $default = false ) {
			return get_option( $this->get_option_name( $submodule, $suffix, $key ), $default );
		}
	}

endif;
