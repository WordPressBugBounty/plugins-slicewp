<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

$payout_methods = slicewp_get_payout_methods();

/**
 * @todo - In a future update, when affiliates will have the option to select their preferred payout method, this switch will be added.
 * 		   Until then, it isn't needed, as we don't have any affiliate facing functionality when it comes to payout methods.
 * 		   The affiliate's selected payout method is either the default, or the one selected by the admin from the affiliate's edit page.
 * 		   Once the affiliate is able to select their preferred payout method themselves, from their affiliate account, we will need to offer admins the option to select which payout methods are available for use and selection.
 * 
 */						
$payout_methods_enabled = (array) get_option( 'slicewp_payout_methods_enabled', array() );

?>

<!-- General Settings -->
<div id="slicewp-card-payouts-general-settings" class="slicewp-card slicewp-first">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo esc_html__( 'General Settings', 'slicewp' ); ?></span>

		<div class="slicewp-card-actions">
			<a href="https://slicewp.com/docs/paying-your-affiliates/" target="_blank" class="slicewp-button-info" title="<?php echo esc_attr( __( 'Click to learn more...', 'slicewp' ) ); ?>"><svg height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path></g></svg></a>
		</div>
	</div>

	<div class="slicewp-card-inner">

		<!-- Default Payout Method -->
		<?php if ( count( $payout_methods ) > 1 ): ?>

			<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

				<div class="slicewp-field-label-wrapper">
					<label for="slicewp-default-payout-method">
						<?php echo __( 'Default Payout Method', 'slicewp' ); ?>
					</label>
				</div>

				<select id="slicewp-default-payout-method" class="slicewp-select2" name="settings[default_payout_method]">

					<?php foreach ( $payout_methods as $method_slug => $method_data ): ?>
						<option value="<?php echo esc_attr( $method_slug ); ?>" <?php echo selected( ( ! empty( $_POST['settings']['default_payout_method'] ) ? $_POST['settings']['default_payout_method'] : ( empty( $_POST ) ? slicewp_get_setting( 'default_payout_method' ) : '' ) ) , $method_slug ); ?>><?php echo esc_html( $method_data['label'] ); ?></option>
					<?php endforeach; ?>

				</select>

			</div>

		<?php else: ?>

			<input type="hidden" name="settings[default_payout_method]" value="<?php echo esc_attr( count( $payout_methods ) == 1 && array_key_exists( ( ! empty( $_POST['settings']['default_payout_method'] ) ? $_POST['settings']['default_payout_method'] : ( empty( $_POST ) ? slicewp_get_setting( 'default_payout_method' ) : 'manual' ) ), $payout_methods ) ? : 'manual' ); ?>" />

		<?php endif; ?>
		<!-- / Default Payout Method -->

		<!-- Payments Minimum Amount -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-payments-minimum-amount">
					<?php echo __( 'Payments Minimum Amount', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( '<p>' . sprintf( __( 'Set a minimum commissions total amount that your affiliates need to reach to be eligible for payment.', 'slicewp' ) ) . '</p>' . '<hr />' . '<a href="https://slicewp.com/docs/paying-your-affiliates/#payments-minimum-amount" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<div class="slicewp-field-currency-amount">
				<div class="slicewp-field-currency-symbol"><?php echo slicewp_get_currency_symbol( slicewp_get_setting( 'active_currency', 'USD' ) ); ?></div>
				<input id="slicewp-payments-minimum-amount" name="settings[payments_minimum_amount]" type="number" value="<?php echo( ! empty( $_POST['settings']['payments_minimum_amount'] ) ? esc_attr( $_POST['settings']['payments_minimum_amount'] ) : ( ! empty( slicewp_get_setting( 'payments_minimum_amount' ) ) ? slicewp_get_setting( 'payments_minimum_amount' ) : 0 ) ); ?>">
			</div>

		</div><!-- / Payments Minimum Amount -->

		<!-- Refund Grace Period -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide slicewp-last">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-refund-grace-period">
					<?php echo __( 'Refund Grace Period', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( '<p>' . sprintf( __( 'The grace period (set in number of days) is used when generating payouts for your affiliates. It helps you filter out commissions that could still be rejected due to a refund of the underlying purchase. We recommend you to set this equal to your store refund policy.', 'slicewp' ) ) . '</p>' . '<hr />' . '<a href="https://slicewp.com/docs/paying-your-affiliates/#refund-grace-period" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<input id="slicewp-refund-grace-period" name="settings[commissions_grace_period]" type="number" value="<?php echo( ! empty( $_POST['settings']['commissions_grace_period'] ) ? esc_attr( $_POST['settings']['commissions_grace_period'] ) : ( ! empty( slicewp_get_setting( 'commissions_grace_period' ) ) ? slicewp_get_setting( 'commissions_grace_period' ) : 0 ) ); ?>">

		</div><!-- / Refund Grace Period -->

		<?php

			/**
			 * Hook to add extra fields to the Payout Methods General Settings card.
			 *
			 */
			do_action( 'slicewp_view_settings_tab_payouts_general_settings_bottom' );

		?>

	</div>

</div><!-- / General Settings -->

<!-- Payout Methods -->
<?php

if ( ! empty( $payout_methods ) ):

?>

<div id="slicewp-card-payout-methods" class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo esc_html__( 'Payout Methods', 'slicewp' ); ?></span>

		<div class="slicewp-card-actions">
			<a href="https://slicewp.com/docs/paying-your-affiliates/" target="_blank" class="slicewp-button-info" title="<?php echo esc_attr( __( 'Click to learn more...', 'slicewp' ) ); ?>"><svg height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path></g></svg></a>
		</div>
	</div>

	<div class="slicewp-card-inner slicewp-expandable-items-wrapper">

		<?php foreach ( $payout_methods as $method_slug => $method_data ): ?>

			<?php

				ob_start();

				/**
				 * Action to output configuration fields for the given payout method's expandable panel.
				 *
				 */
				do_action( 'slicewp_view_settings_tab_payouts_payout_method_panel_' . $method_slug );

				$panel_content = ob_get_clean();

			?>

			<div class="slicewp-expandable-item">

				<div class="slicewp-expandable-item-header">

					<div>
						<?php if ( ! empty( $method_data['icon'] ) ) echo '<div class="slicewp-payout-method-icon">' . wp_kses( $method_data['icon'], slicewp_get_kses_allowed_html() ) . '</div>'; ?>
					</div>

					<div>

						<?php
							/**
							 * @todo - In a future update, when affiliates will have the option to select their preferred payout method, this switch will be added.
							 * 		   Until then, it isn't needed, as we don't have any affiliate facing functionality when it comes to payout methods.
							 * 		   The affiliate's selected payout method is either the default, or the one selected by the admin from the affiliate's edit page.
							 * 		   Once the affiliate is able to select their preferred payout method themselves, from their affiliate account, we will need to offer admins the option to select which payout methods are available for use and selection.
							 * 
							 */
						?>
						<?php /*
						<div class="slicewp-switch">

							<input
								id="slicewp-payout-method-enabled-<?php echo esc_attr( $method_slug ); ?>"
								class="slicewp-toggle slicewp-toggle-round"
								name="payout_methods_enabled[<?php echo esc_attr( $method_slug ); ?>]"
								type="checkbox"
								value="1"
								<?php checked( in_array( $method_slug, $payout_methods_enabled ) ); ?>
							/>
							<label for="slicewp-payout-method-enabled-<?php echo esc_attr( $method_slug ); ?>"></label>

						</div>
						*/ ?>

						<label for="slicewp-payout-method-enabled-<?php echo esc_attr( $method_slug ); ?>">
							<?php echo esc_html( $method_data['label'] ); ?>
							<?php if ( 'manual' !== $method_slug && ! in_array( 'integrated_payment_processing', (array) $method_data['supports'] ) ): ?>
								<span class="slicewp-payout-method-badge"><?php echo esc_html__( 'Manual', 'slicewp' ); ?></span>
							<?php endif; ?>
						</label>

					</div>

					<div class="slicewp-expandable-item-actions">
						<?php if ( $panel_content ): ?>
							<a href="#" class="slicewp-expand-item"><?php echo esc_html__( 'Configure', 'slicewp' ); ?><?php echo slicewp_get_svg( 'outline-chevron-down' ); ?></a>
						<?php endif; ?>
					</div>

				</div>

				<div class="slicewp-expandable-item-panel">

					<?php

						echo $panel_content;

						unset( $panel_content );

					?>

				</div>

			</div>

		<?php endforeach; ?>

		<?php do_action( 'slicewp_view_settings_tab_payouts_payout_methods_bottom' ); ?>

	</div>

</div>
<!-- / Payout Methods -->

<?php endif; ?>

<?php

	/**
	 * Hook to add extra cards to the Payouts settings tab.
	 *
	 */
	do_action( 'slicewp_view_settings_tab_payouts_bottom' );

?>

<!-- Save Settings Button -->
<input type="submit" class="slicewp-form-submit slicewp-button-primary" value="<?php echo esc_html__( 'Save Settings', 'slicewp' ); ?>" />