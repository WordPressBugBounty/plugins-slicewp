<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$payments_preview = slicewp_generate_payout_payments_preview( $_GET );

// Split the candidates into those ready to be paid — which is what creating the payout will
// actually generate — and those whose selected payout method still needs setup, so the admin
// sees both groups clearly.
$payments_payable  = array();
$payments_excluded = array();

foreach ( $payments_preview as $payment_preview ) {

	if ( slicewp_affiliate_has_completed_payout_setup( $payment_preview['affiliate_id'] ) ) {
		$payments_payable[] = $payment_preview;
	} else {
		$payments_excluded[] = $payment_preview;
	}

}

// Sort the payable payments by amount, highest first.
$payable_amounts = array_column( $payments_payable, 'amount' );
array_multisort( $payable_amounts, SORT_DESC, $payments_payable );

$payments_totals = array_sum( array_column( $payments_payable, 'amount' ) );
$payments_count  = count( $payments_payable );

// Get payout methods.
$payout_methods = slicewp_get_payout_methods();

// Get active currency.
$currency = slicewp_get_setting( 'active_currency', 'USD' );

// Per-method payable totals, for the summary line in the Payout Total card.
$payable_by_method = array();

foreach ( $payments_payable as $payment_preview ) {
	$method_slug                       = slicewp_get_affiliate_payout_method( $payment_preview['affiliate_id'] );
	$payable_by_method[ $method_slug ] = ( isset( $payable_by_method[ $method_slug ] ) ? $payable_by_method[ $method_slug ] : 0 ) + (float) $payment_preview['amount'];
}

// Human-readable recap of the criteria this preview was generated from.
$preview_recap = array();

if ( ! empty( $_GET['date_range'] ) && 'up_to' === $_GET['date_range'] ) {
	$preview_recap[] = sprintf( __( 'Commissions up to %s', 'slicewp' ), date_i18n( get_option( 'date_format' ), strtotime( ! empty( $_GET['date_up_to'] ) ? sanitize_text_field( $_GET['date_up_to'] ) : date( 'Y-m-d' ) ) ) );
} elseif ( ! empty( $_GET['date_range'] ) && 'custom_range' === $_GET['date_range'] ) {
	$preview_recap[] = sprintf( __( 'Commissions from %1$s to %2$s', 'slicewp' ), ( ! empty( $_GET['date_min'] ) ? date_i18n( get_option( 'date_format' ), strtotime( sanitize_text_field( $_GET['date_min'] ) ) ) : '&hellip;' ), ( ! empty( $_GET['date_max'] ) ? date_i18n( get_option( 'date_format' ), strtotime( sanitize_text_field( $_GET['date_max'] ) ) ) : '&hellip;' ) );
}

$preview_minimum_amount = isset( $_GET['payments_minimum_amount'] ) ? slicewp_sanitize_amount( $_GET['payments_minimum_amount'] ) : 0;

if ( $preview_minimum_amount > 0 ) {
	$preview_recap[] = sprintf( __( 'Minimum %s', 'slicewp' ), slicewp_format_amount( $preview_minimum_amount, $currency ) );
}

if ( ! empty( $_GET['included_affiliates'] ) && 'selected' === $_GET['included_affiliates'] && ! empty( $_GET['selected_affiliates'] ) ) {
	$selected_affiliates_count = count( (array) $_GET['selected_affiliates'] );
	$preview_recap[]           = sprintf( _n( '%d selected affiliate', '%d selected affiliates', $selected_affiliates_count, 'slicewp' ), $selected_affiliates_count );
} else {
	$preview_recap[] = __( 'All affiliates', 'slicewp' );
}

?>

<div class="wrap slicewp-wrap slicewp-wrap-preview-payout">

	<form method="POST">

		<!-- Page Heading -->
		<h1 class="wp-heading-inline"><?php echo __( 'Preview Payout', 'slicewp' ); ?></h1>
		<a href="<?php echo esc_url( add_query_arg( array_diff_key( array_merge( $_GET, array( 'subpage' => 'create-payout' ) ), array_flip( array( 'payments_count', 'payout_amount' ) ) ), $this->admin_url ) ); ?>" class="page-title-action"><?php echo '&#x2190; ' . __( 'Back to Payout Details', 'slicewp' ); ?></a>
		<hr class="wp-header-end" />

		<?php if ( ! empty( $preview_recap ) ): ?>
			<p style="max-width: 675px; margin: 12px 0 0; color: #646970; font-size: 13px;"><?php echo implode( ' &middot; ', $preview_recap ); ?></p>
		<?php endif; ?>

		<?php if ( empty( $payments_payable ) && empty( $payments_excluded ) ): ?>

			<div class="notice-warning notice slicewp-notice" style="max-width: 675px; margin-top: 25px;">
				<p><?php echo __( 'No affiliate payments could be generated for the selected payout details.', 'slicewp' ); ?></p>
			</div>

		<?php else: ?>

			<?php if ( ! empty( $payments_payable ) ): ?>

				<div class="slicewp-card">

					<div class="slicewp-card-header" style="font-weight: normal;">

						<span style="font-size: 14px;"><?php echo __( 'Payout Total', 'slicewp' ); ?></span>
						<div style="font-size: 28px; line-height: 1; margin-top: 15px; margin-bottom: 5px;"><?php echo slicewp_format_amount( $payments_totals, $currency ); ?></div>
						<div style="font-size: 13px;"><?php echo esc_html( sprintf( _n( '%d affiliate payment', '%d affiliate payments', $payments_count, 'slicewp' ), $payments_count ) ); ?></div>

					</div>

					<div class="slicewp-card-inner">

						<table class="slicewp-card-table-full-width slicewp-card-table-payout-preview-payments">

							<thead>
								<tr>
									<th class="slicewp-column-affiliate"><?php echo esc_html( __( 'Affiliate', 'slicewp' ) ); ?></th>
									<th class="slicewp-column-amount"><?php echo esc_html( __( 'Amount', 'slicewp' ) ); ?></th>
									<th class="slicewp-column-commissions"><?php echo esc_html( __( 'Commissions', 'slicewp' ) ); ?></th>
									<th class="slicewp-column-payout-method"><?php echo esc_html( __( 'Payout Method', 'slicewp' ) ); ?></th>
								</tr>
							</thead>

							<tbody>

								<?php foreach ( $payments_payable as $payment ): ?>

									<tr>

										<td class="slicewp-column-affiliate">

											<?php

												$affiliate 		= slicewp_get_affiliate( absint( $payment['affiliate_id'] ) );
												$affiliate_name = ( ! is_null( $affiliate ) ? slicewp_get_affiliate_name( $affiliate ) : '' );

												if ( ! is_null( $affiliate ) ) {

													echo '<a class="slicewp-affiliate-name" href="' . esc_url( add_query_arg( array( 'page' => 'slicewp-affiliates', 'subpage' => 'edit-affiliate', 'affiliate_id' => absint( $affiliate->get( 'id' ) ) ) , admin_url( 'admin.php' ) ) ) . '">';
														echo get_avatar( $affiliate->get( 'user_id' ), 64 );
														echo '<span>' . esc_html( $affiliate_name ) . '</span>';
													echo '</a>';

												} else {

													echo __( '(inexistent affiliate)', 'slicewp' );

												}

											?>

										</td>

										<td class="slicewp-column-amount">
											<?php echo slicewp_format_amount( $payment['amount'], $payment['currency'] ); ?>
										</td>

										<td class="slicewp-column-commissions">
											<?php echo count( $payment['commission_ids'] ); ?>
										</td>

										<td class="slicewp-column-payout-method">

											<?php

												$affiliate_payout_method = slicewp_get_affiliate_payout_method( $payment['affiliate_id'] );
												$method_label            = ! empty( $payout_methods[ $affiliate_payout_method ]['label'] ) ? $payout_methods[ $affiliate_payout_method ]['label'] : $affiliate_payout_method;
												$method_icon             = ! empty( $payout_methods[ $affiliate_payout_method ]['icon'] ) ? $payout_methods[ $affiliate_payout_method ]['icon'] : '';

												echo '<div class="slicewp-payout-method">';

													if ( ! empty( $method_icon ) ) {
														echo $method_icon;
													}

													echo esc_html( $method_label );
												
												echo '</div>';

											?>

										</td>

									</tr>

								<?php endforeach; ?>

							</tbody>

						</table>

					</div>

					<?php if ( count( $payable_by_method ) > 1 ): ?>

						<div class="slicewp-card-footer">

							<div style="font-size: 12px;">

								<?php

									$method_summary = array();

									foreach ( $payable_by_method as $method_slug => $method_amount ) {
										$method_summary[] = sprintf( __( '%1$s via %2$s', 'slicewp' ), slicewp_format_amount( $method_amount, $currency ), esc_html( ! empty( $payout_methods[ $method_slug ]['label'] ) ? $payout_methods[ $method_slug ]['label'] : $method_slug ) );
									}

									echo implode( ' &middot; ', $method_summary );

								?>

							</div>

						</div>

					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( ! empty( $payments_excluded ) ): ?>

				<?php
					$excluded_count  = count( $payments_excluded );
					$excluded_totals = array_sum( array_column( $payments_excluded, 'amount' ) );
				?>

				<div class="slicewp-card">

					<div class="slicewp-card-header" style="font-weight: normal;">

						<div class="slicewp-field-notice slicewp-field-notice-warning" style="display: block; margin: 0; padding: 15px;">

							<div style="display: flex; gap: 10px;">

								<div>
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" style="height: 24px; width: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
								</div>

								<div>
									<strong style="display: block; margin-bottom: 8px;"><?php echo esc_html( __( 'Unpayable affiliates', 'slicewp' ) ); ?></strong>
									<span><?php echo sprintf( _n( 'The following %1$d affiliate has earnings totalling %2$s, but can\'t be paid because their payout method isn\'t set up. Because of this, they won\'t be included in this payout.', 'The following %1$d affiliates have earnings totalling %2$s, but can\'t be paid because their payout method isn\'t set up. Because of this, they won\'t be included in this payout.', $excluded_count, 'slicewp' ), $excluded_count, slicewp_format_amount( $excluded_totals, $currency ) ); ?></span>
								</div>

							</div>

						</div>

					</div>

					<div class="slicewp-card-inner">

						<table class="slicewp-card-table-full-width slicewp-card-table-payout-preview-payments">

							<thead>
								<tr>
									<th class="slicewp-column-affiliate"><?php echo esc_html( __( 'Affiliate', 'slicewp' ) ); ?></th>
									<th class="slicewp-column-amount"><?php echo esc_html( __( 'Amount', 'slicewp' ) ); ?></th>
									<th class="slicewp-column-commissions"><?php echo esc_html( __( 'Commissions', 'slicewp' ) ); ?></th>
									<th class="slicewp-column-payout-method"><?php echo esc_html( __( 'Payout Method', 'slicewp' ) ); ?></th>
								</tr>
							</thead>

							<tbody>

								<?php foreach ( $payments_excluded as $payment ): ?>

									<tr>

										<td class="slicewp-column-affiliate">

											<?php

												$affiliate      = slicewp_get_affiliate( absint( $payment['affiliate_id'] ) );
												$affiliate_name = ( ! is_null( $affiliate ) ? slicewp_get_affiliate_name( $affiliate ) : '' );

												if ( ! is_null( $affiliate ) ) {

													echo '<a class="slicewp-affiliate-name" href="' . esc_url( add_query_arg( array( 'page' => 'slicewp-affiliates', 'subpage' => 'edit-affiliate', 'affiliate_id' => absint( $affiliate->get( 'id' ) ) ) , admin_url( 'admin.php' ) ) ) . '">';
														echo get_avatar( $affiliate->get( 'user_id' ), 64 );
														echo '<span>' . esc_html( $affiliate_name ) . '</span>';
													echo '</a>';

												} else {

													echo __( '(inexistent affiliate)', 'slicewp' );

												}

											?>

										</td>

										<td class="slicewp-column-amount">
											<?php echo slicewp_format_amount( $payment['amount'], $payment['currency'] ); ?>
										</td>

										<td class="slicewp-column-commissions">
											<?php echo count( $payment['commission_ids'] ); ?>
										</td>

										<td class="slicewp-column-payout-method">

											<?php

												$affiliate_payout_method = slicewp_get_affiliate_payout_method( $payment['affiliate_id'] );
												$method_label            = ! empty( $payout_methods[ $affiliate_payout_method ]['label'] ) ? $payout_methods[ $affiliate_payout_method ]['label'] : $affiliate_payout_method;
												$method_icon             = ! empty( $payout_methods[ $affiliate_payout_method ]['icon'] ) ? $payout_methods[ $affiliate_payout_method ]['icon'] : '';

												echo '<div class="slicewp-payout-method">';

													if ( ! empty( $method_icon ) ) {
														echo $method_icon;
													}

													echo esc_html( $method_label );

												echo '</div>';

											?>

										</td>

									</tr>

								<?php endforeach; ?>

							</tbody>

						</table>

					</div>

				</div>

			<?php endif; ?>

			<?php if ( ! empty( $payments_payable ) ): ?>

				<!-- Hidden fields needed for the search query -->
				<input type="hidden" name="page" value="slicewp-payments">

				<!-- Action and nonce -->
				<input type="hidden" name="slicewp_action" value="create_payout" />
				<?php wp_nonce_field( 'slicewp_create_payout', 'slicewp_token', false ); ?>

				<!-- Submit -->
				<button type="submit" class="slicewp-form-submit slicewp-button-primary" name="slicewp_create_payout" value="1"><?php printf( __( 'Create Payout &middot; %s', 'slicewp' ), slicewp_format_amount( $payments_totals, $currency ) ); ?></button>

			<?php endif; ?>

		<?php endif; ?>

	</form>

</div>