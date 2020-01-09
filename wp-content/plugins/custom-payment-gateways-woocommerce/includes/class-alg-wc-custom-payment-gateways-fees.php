<?php
/**
 * Custom Payment Gateways for WooCommerce - Fees Class
 *
 * @version 1.6.0
 * @since   1.6.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Payment_Gateways_Fees' ) ) :

class Alg_WC_Custom_Payment_Gateways_Fees {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [feature] discounts
	 * @todo    [feature] per product, per category, per tag
	 */
	function __construct() {
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_fees' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts',              array( $this, 'scripts' ) );
	}

	/**
	 * scripts.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function scripts() {
		wp_enqueue_script( 'alg-wc-custom-payment-gateways',
			alg_wc_custom_payment_gateways()->plugin_url() . '/includes/js/alg-wc-custom-payment-gateways.js',
			array( 'jquery' ),
			alg_wc_custom_payment_gateways()->version,
			true
		);
	}

	/**
	 * calculate_fees.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function calculate_fees( $cart ) {
		foreach ( $this->get_fees( $cart ) as $fee ) {
			$cart->add_fee( $fee['name'], $fee['amount'], $fee['taxable'], $fee['tax_class'] );
		}
	}

	/**
	 * is_equal.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (maybe) better epsilon value
	 */
	function is_equal( $float1, $float2 ) {
		$epsilon = ( defined( 'PHP_FLOAT_EPSILON' ) ? PHP_FLOAT_EPSILON : 0.000001 );
		return ( abs( $float1 - $float2 ) < $epsilon );
	}

	/**
	 * get_fees.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (maybe) check if we really will need `$cart_total` before calculating it
	 */
	function get_fees( $cart ) {
		$fees = array();
		if ( ( $current_gateway = $this->get_current_gateway() ) && 'WC_Gateway_Alg_Custom_Template' === get_class( $current_gateway ) ) {
			$cart_total = $this->get_cart_total( $cart );
			$fees_data  = $current_gateway->get_fees();
			foreach ( $fees_data as $fee ) {
				if (
					( $fee['cart_min'] && ! $this->equal( $cart_total, $fee['cart_min'] ) && $cart_total < $fee['cart_min'] ) ||
					( $fee['cart_max'] && ! $this->equal( $cart_total, $fee['cart_max'] ) && $cart_total > $fee['cart_max'] )
				) {
					continue;
				}
				if ( 'percent' === $fee['type'] ) {
					$fee['amount'] = $cart_total * $fee['amount'] / 100;
					if ( $min = $fee['amount_min'] ) {
						$fee['amount'] = max( $fee['amount'], $min );
					}
					if ( $max = $fee['amount_max'] ) {
						$fee['amount'] = min( $fee['amount'], $max );
					}
				}
				$is_added = false;
				foreach ( $fees as $key => $_fee ) {
					if ( $_fee['name'] === $fee['name'] ) {
						// Merge fees with same title
						$fees[ $key ]['amount'] += $fee['amount'];
						$is_added = true;
					}
				}
				if ( ! $is_added ) {
					$fees[] = $fee;
				}
			}
		}
		return $fees;
	}

	/**
	 * get_cart_total.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] check if we need to also call `calculate_shipping()`
	 */
	function get_cart_total( $cart ) {
		// Calculate totals
		remove_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_fees' ), PHP_INT_MAX );
		$cart->calculate_totals();
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_fees' ), PHP_INT_MAX );
		// Options
		$do_include_taxes     = ( 'yes' === get_option( 'alg_wc_cpg_fees_cart_total_taxes',     'yes' ) );
		$do_include_shipping  = ( 'yes' === get_option( 'alg_wc_cpg_fees_cart_total_shipping',  'yes' ) );
		$do_include_discounts = ( 'yes' === get_option( 'alg_wc_cpg_fees_cart_total_discounts', 'yes' ) );
		// Subtotal (i.e. before discounts)
		$cart_total = $cart->get_subtotal();
		if ( $do_include_taxes ) {
			// Include taxes
			$cart_total += $cart->get_subtotal_tax();
		}
		// Shipping
		if ( $do_include_shipping ) {
			$cart_total += $cart->get_shipping_total();
			if ( $do_include_taxes ) {
				// Include taxes
				$cart_total += $cart->get_shipping_tax();
			}
		}
		// Discounts
		if ( $do_include_discounts ) {
			$cart_total -= $cart->get_discount_total();
			if ( $do_include_taxes ) {
				// Include taxes
				$cart_total -= $cart->get_discount_tax();
			}
		}
		return $cart_total;
	}

	/**
	 * get_current_gateway.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 * @todo    [dev] (important) simplify
	 * @todo    [dev] (maybe) add `$this->last_known_current_gateway` fallback
	 */
	function get_current_gateway() {
		// Get the key
		$current_gateway_key = false;
		if ( isset( WC()->session->chosen_payment_method ) ) {
			$current_gateway_key = WC()->session->chosen_payment_method;
		} elseif ( ! empty( $_REQUEST['payment_method'] ) ) {
			$current_gateway_key = sanitize_key( $_REQUEST['payment_method'] );
		} elseif ( '' != get_option( 'woocommerce_default_gateway' ) ) {
			$current_gateway_key = get_option( 'woocommerce_default_gateway' );
		}
		// Get the object
		$current_gateway    = false;
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		if ( ! empty( $available_gateways ) ) {
			if ( $current_gateway_key && isset( $available_gateways[ $current_gateway_key ] ) ) {
				$current_gateway = $available_gateways[ $current_gateway_key ];
			} else {
				$current_gateway = current( $available_gateways );
			}
		}
		return $current_gateway;
	}

}

endif;

return new Alg_WC_Custom_Payment_Gateways_Fees();