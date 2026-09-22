<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register "Manual" payout method.
 *
 * The manual payout method is registered very late and should always be included for everything to work nicely.
 *
 * @param array $payout_methods
 *
 * @return array
 *
 */
function slicewp_register_payout_method_manual( $payout_methods ) {

	if ( ! is_array( $payout_methods ) ) {
		$payout_methods = array();
	}

	$payout_methods = array_reverse( $payout_methods );

	$payout_methods['manual'] = array(
		'label'    => __( 'Manual', 'slicewp' ),
		'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" /></svg>',
		'supports' => array( 'single_payment', 'bulk_payments', 'payout_request_invoice' ),
		'messages' => array(
			'payout_action_confirmation_bulk_payments' => __( 'This will mark all unpaid and failed payments for this payout as paid. All commissions associated with these payments will also be marked as paid. Are you sure you want to continue?', 'slicewp' )
		)
	);

	return array_reverse( $payout_methods );

}
add_filter( 'slicewp_register_payout_methods', 'slicewp_register_payout_method_manual', 999 );


/**
 * Checks to see whether within a payout the "manual" payout method can performs bulk payments.
 * 
 * @param bool 	 $can_do
 * @param int 	 $payout_id
 * @param string $payout_method
 * 
 * @return bool
 * 
 */
function slicewp_can_do_bulk_payments_payout_method_manual( $can_do, $payout_id, $payout_method ) {

	if ( 'manual' != $payout_method ) {
		return $can_do;
	}

	$payments = slicewp_get_payments( array( 'payout_id' => $payout_id, 'payout_method' => $payout_method, 'status' => 'unpaid' ), true );

	if ( ! empty( $payments ) ) {
		$can_do = true;
	}

	return $can_do;

}
add_filter( 'slicewp_can_do_bulk_payments', 'slicewp_can_do_bulk_payments_payout_method_manual', 10, 3 );


/**
 * Handles "manual" bulk payments.
 *
 * @param int $payout_id
 *
 */
function slicewp_do_bulk_payments_manual( $payout_id ) {

	$payments_ids = slicewp_get_payments( array( 'number' => -1, 'payout_id' => $payout_id, 'payout_method' => 'manual', 'status' => 'unpaid', 'fields' => 'id' ) );

	// Go through each payment and mark it as paid.
	foreach ( $payments_ids as $payment_id ) {

		// Update payment.
		$payment_data = array(
			'date_modified' => slicewp_mysql_gmdate(),
			'status' 		=> 'paid'
		);

		$updated = slicewp_update_payment( $payment_id, $payment_data );

		// If the payment wasn't updated, go to next payment
		if ( ! $updated ) {
			continue;
		}

		// If the payment was updated, update each of the generated commissions.
		$commission_ids = slicewp_get_commissions( array( 'number' => -1, 'payment_id' => $payment_id, 'fields' => 'id' ) );
		$commission_ids = array_map( 'absint', $commission_ids );

		$commission_data = array(
			'date_modified' => slicewp_mysql_gmdate(),
			'status'		=> 'paid'
		);

		foreach ( $commission_ids as $commission_id ) {

			$updated = slicewp_update_commission( $commission_id, $commission_data );
		
		}

	}

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'slicewp-payouts', 'subpage' => 'view-payout', 'payout_id' => absint( $payout_id ), 'slicewp_message' => 'payout_bulk_payments_manual_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'slicewp_do_bulk_payments_manual', 'slicewp_do_bulk_payments_manual', 10, 2 );


/**
 * Adds extra HTML after the "Payout Method" field found in the do bulk payments admin form.
 * 
 * @param int $payout_id
 * 
 */
function slicewp_form_do_bulk_payments_payout_method_bottom_manual( $payout_id ) {

	if ( empty( $payout_id ) ) {
		return;
	}

	$payments = slicewp_get_payments( array( 'number' => -1, 'payout_id' => $payout_id, 'payout_method' => 'manual', 'status' => 'unpaid', 'fields' => 'amount' ) );

	if ( empty( $payments ) ) {
		return;
	}

	?>

		<div class="slicewp-select2-option-selection-description" data-option="manual">
			<p><?php echo sprintf( _n( 'There is %d unpaid manual payment in this payout, totaling %s.', 'There are %d unpaid manual payments in this payout, totaling %s.', count( $payments ), 'slicewp' ), count( $payments ), slicewp_format_amount( array_sum( $payments ), slicewp_get_setting( 'active_currency', 'USD' ) ) ); ?></p>
			<p><?php echo __( 'By clicking the button below, you can mark these payments as paid.', 'slicewp' ) ?></p>
		</div>

	<?php

}
add_action( 'slicewp_form_do_bulk_payments_payout_method_bottom', 'slicewp_form_do_bulk_payments_payout_method_bottom_manual' );


/**
 * Outputs the Payment Email field inside the Payout Details card on the admin affiliate add/edit forms.
 *
 * @param string $context     'add_affiliate' or 'edit_affiliate'
 * @param int    $affiliate_id
 *
 */
function slicewp_view_affiliates_payout_details_fields_manual( $context, $affiliate_id ) {

	$affiliate     = $affiliate_id ? slicewp_get_affiliate( $affiliate_id ) : null;
	$payment_email = $affiliate ? $affiliate->get( 'payment_email' ) : '';

	?>

		<div data-payout-method="manual" style="display:none;">

			<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

				<div class="slicewp-field-label-wrapper">
					<label for="slicewp-affiliate-payment-email-manual"><?php echo __( 'Payment Email', 'slicewp' ); ?></label>
				</div>

				<input id="slicewp-affiliate-payment-email-manual" name="payment_email" type="email" value="<?php echo esc_attr( isset( $_POST['payment_email'] ) ? sanitize_email( $_POST['payment_email'] ) : $payment_email ); ?>" />

			</div>

		</div>

	<?php

}
add_action( 'slicewp_view_affiliates_payout_details_fields', 'slicewp_view_affiliates_payout_details_fields_manual', 5, 2 );


/**
 * Outputs the Payment Email field in the Payout Details section of the affiliate account
 * settings form for affiliates on the Manual payout method.
 *
 * Only runs when default fields are active. When the Custom Affiliate Fields add-on is
 * configured, payment_email remains in the generic field loop as the admin arranged it.
 *
 * @param int $affiliate_id
 *
 */
function slicewp_affiliate_account_payout_method_setup_manual( $affiliate_id ) {

	if ( 'manual' !== slicewp_get_affiliate_payout_method( $affiliate_id ) ) {
		return;
	}

	if ( ! has_filter( 'slicewp_register_affiliate_fields', 'slicewp_register_default_affiliate_fields' ) ) {
		return;
	}

	$affiliate     = slicewp_get_affiliate( $affiliate_id );
	$payment_email = $affiliate ? $affiliate->get( 'payment_email' ) : '';

	$error_message = slicewp_form_errors()->get_error_message( 'payment_email' );

	?>

		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

			<div class="slicewp-field-label-wrapper">

				<label for="slicewp-affiliate-payment-email">
					<?php echo esc_html__( 'Payment Email', 'slicewp' ); ?>
					<span class="slicewp-field-required-marker">*</span>
				</label>

			</div>

			<div class="slicewp-field-inner">

				<input id="slicewp-affiliate-payment-email" name="payment_email" type="email" value="<?php echo esc_attr( isset( $_POST['payment_email'] ) ? sanitize_email( $_POST['payment_email'] ) : $payment_email ); ?>" />

				<?php if ( ! empty( $error_message ) ): ?>

					<div class="slicewp-field-error-message">
						<p><?php echo wp_kses_post( $error_message ); ?></p>
					</div>
				
				<?php endif; ?>
			
			</div>

		</div>

	<?php

}
add_action( 'slicewp_affiliate_account_payout_method_setup', 'slicewp_affiliate_account_payout_method_setup_manual', 5 );