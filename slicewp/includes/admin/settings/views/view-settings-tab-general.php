<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<!-- Register website -->
<div class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'Register Website', 'slicewp' ); ?></span>
	</div>

	<div class="slicewp-card-inner">

		<!-- License Key -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-license-key">
					<?php echo __( 'License Key', 'slicewp' ); ?>
				</label>
			</div>

			<div class="slicewp-flex">

				<?php $license_key = get_option( 'slicewp_license_key', '' ); ?>

				<input id="slicewp-license-key" name="license_key" type="<?php echo ( empty( $license_key ) ? 'text' : 'password' ); ?>" value="<?php echo esc_attr( $license_key ); ?>">
				
				<a id="slicewp-register-license-key" class="slicewp-button-secondary" href="#">
					<span class="slicewp-register" <?php echo ( slicewp_is_website_registered() ? 'style="display: none;"' : '' ); ?>><?php echo __( 'Register', 'slicewp' ); ?></span>
					<span class="slicewp-deregister" <?php echo ( ! slicewp_is_website_registered() ? 'style="display: none;"' : '' ); ?>><?php echo __( 'Deregister', 'slicewp' ); ?></span>
					<div class="spinner"></div>
				</a>
				
			</div>

			<input id="slicewp-is-website-registered" type="hidden" value="<?php echo ( slicewp_is_website_registered() ? 'true' : 'false' ); ?>" />

			<?php if( ! slicewp_is_website_registered() ): ?>
				<div class="slicewp-field-notice slicewp-field-notice-warning" style="display: block;">
					<p><strong><?php echo __( 'Enter your license key.', 'slicewp' ); ?></strong> <?php echo sprintf( __( 'Your license key can be found in your %sSliceWP account%s.', 'slicewp' ), '<a href="https://slicewp.com/account/?utm_source=plugin-free&amp;utm_medium=plugin-card-license-key&amp;utm_campaign=SliceWPFree" target="_blank">', '</a>' ); ?></p>
					<p><?php echo sprintf( __( 'You can use this core version of SliceWP for free. For priority support and advanced functionality, %sa license key is required%s.', 'slicewp' ), '<a href="https://slicewp.com/pricing/?utm_source=plugin-free&amp;utm_medium=plugin-card-license-key&amp;utm_campaign=SliceWPFree" target="_blank">', '</a>' ); ?></p>
				</div>
			<?php endif; ?>

		</div><!-- / License Key -->

	</div>

</div>
<!-- / Register website -->

<!-- Currency Settings -->
<div id="slicewp-card-settings-currency" class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'Currency Settings', 'slicewp' ); ?></span>
	</div>

	<div class="slicewp-card-inner">

		<!-- Currency -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-active-currency">
					<?php echo __( 'Currency', 'slicewp' ); ?>
				</label>
			</div>

			<select id="slicewp-active-currency" name="settings[active_currency]" class="slicewp-select2">
				<?php foreach( slicewp_get_currencies() as $currency_code => $currency_name ): ?>
					<?php $currency_symbol = slicewp_get_currency_symbol( $currency_code ); ?>
					<option value="<?php echo esc_attr( $currency_code ); ?>" <?php echo selected( ! empty( $_POST['settings']['active_currency'] ) ? $_POST['settings']['active_currency'] : ( empty( $_POST ) ? slicewp_get_setting( 'active_currency' ) : '' ), $currency_code ); ?>><?php echo esc_attr( $currency_name ) . ( ! empty( $currency_symbol ) ? ( ' (' . $currency_symbol . ')' ) : '' ); ?></option>
				<?php endforeach; ?>
			</select>

		</div><!-- / Currency -->

		<!-- Currency Symbol Position -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-currency-symbol-position">
					<?php echo __( 'Currency Symbol Position', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'The position of the currency symbol in relation with the amount value, when displaying amounts.', 'slicewp' ) ); ?>
				</label>
			</div>
			
			<select id="slicewp-currency-symbol-position" name="settings[currency_symbol_position]" class="slicewp-select2">
				<option value="before" <?php echo selected( ( ! empty( $_POST['settings']['currency_symbol_position'] ) ? $_POST['settings']['currency_symbol_position'] : ( empty( $_POST ) ? slicewp_get_setting( 'currency_symbol_position' ) : '' ) ) , 'before' ); ?>><?php echo __( 'Before amount', 'slicewp' ); ?></option>
				<option value="after" <?php echo selected( ( ! empty( $_POST['settings']['currency_symbol_position'] ) ? $_POST['settings']['currency_symbol_position'] : ( empty( $_POST ) ? slicewp_get_setting( 'currency_symbol_position' ) : '' ) ) , 'after' ); ?>><?php echo __( 'After amount', 'slicewp' ); ?></option>
				<option value="before_space" <?php echo selected( ( ! empty( $_POST['settings']['currency_symbol_position'] ) ? $_POST['settings']['currency_symbol_position'] : ( empty( $_POST ) ? slicewp_get_setting( 'currency_symbol_position' ) : '' ) ) , 'before_space' ); ?>><?php echo __( 'Before amount with space', 'slicewp' ); ?></option>
				<option value="after_space" <?php echo selected( ( ! empty( $_POST['settings']['currency_symbol_position'] ) ? $_POST['settings']['currency_symbol_position'] : ( empty( $_POST ) ? slicewp_get_setting( 'currency_symbol_position' ) : '' ) ) , 'after_space' ); ?>><?php echo __( 'After amount with space', 'slicewp' ); ?></option>
			</select>

		</div><!-- / Currency Symbol Position -->

		<!-- Thousands Separator -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-currency-thousands-separator">
					<?php echo __( 'Thousands Separator', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'The symbol to separate thousands. This is usually a , (comma) or a . (dot).', 'slicewp' ) ); ?>
				</label>
			</div>

			<input id="slicewp-currency-thousands-separator" name="settings[currency_thousands_separator]" type="text" value="<?php echo esc_attr( ! empty( $_POST['settings']['currency_thousands_separator'] ) ? $_POST['settings']['currency_thousands_separator'] : ( empty( $_POST ) ? slicewp_get_setting( 'currency_thousands_separator' ) : '' ) ); ?>">

		</div><!-- / Thousands Separator -->

		<!-- Decimal Separator -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-currency-decimal-separator">
					<?php echo __( 'Decimal Separator', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'The symbol to separate decimal points. This is usually a , (comma) or a . (dot).', 'slicewp' ) ); ?>
				</label>
			</div>

			<input id="slicewp-currency-decimal-separator" name="settings[currency_decimal_separator]" type="text" value="<?php echo esc_attr( ! empty( $_POST['settings']['currency_decimal_separator'] ) ? $_POST['settings']['currency_decimal_separator'] : ( empty( $_POST ) ? slicewp_get_setting( 'currency_decimal_separator' ) : '' ) ); ?>">

		</div><!-- / Decimal Separator -->

	</div>

</div><!-- / Currency Settings -->

<?php 

	/**
	 * Hook to add extra cards if needed to the General Settings tab
	 *
	 */
	do_action( 'slicewp_view_settings_tab_general_bottom' );

	/**
	 * Hook to add extra cards if needed to the General Settings tab
	 *
	 * @deprecated 1.0.12 - No longer used in core and not recommended for external usage.
	 * 					    Replaced by "slicewp_view_settings_tab_general_bottom" action.
	 *					    Slated for removal in version 2.0.0
	 *
	 */
	do_action( 'slicewp_view_settings_tab_bottom_general' );

?>

<!-- Save Settings Button -->
<input type="submit" class="slicewp-form-submit slicewp-button-primary" value="<?php echo __( 'Save Settings', 'slicewp' ); ?>" />