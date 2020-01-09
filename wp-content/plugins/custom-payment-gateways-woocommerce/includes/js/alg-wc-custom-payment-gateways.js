/**
 * alg-wc-custom-payment-gateways.js
 *
 * @version 1.6.0
 * @since   1.6.0
 * @author  Algoritmika Ltd.
 */

jQuery( function() {
	jQuery( 'body' ).on( 'change', 'input[name="payment_method"]', function() {
		jQuery( 'body' ).trigger( 'update_checkout' );
	} );
} );
