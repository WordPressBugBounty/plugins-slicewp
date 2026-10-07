<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<!-- Registration -->
<div id="slicewp-card-affiliate-area-registration" class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'Registration', 'slicewp' ); ?></span>
	</div>

	<div class="slicewp-card-inner">

		<!-- Allow Affiliate Registration -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-allow-affiliate-registration">
					<?php echo __( 'Allow Affiliate Registration', 'slicewp' ); ?>
				</label>
			</div>

			<div class="slicewp-switch">

				<input id="slicewp-allow-affiliate-registration" class="slicewp-toggle slicewp-toggle-round" name="settings[allow_affiliate_registration]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['allow_affiliate_registration'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'allow_affiliate_registration' ) : '' ), '1' ); ?> />
				<label for="slicewp-allow-affiliate-registration"></label>

			</div>

			<label for="slicewp-allow-affiliate-registration"><?php echo __( 'Allow visitors to register as affiliates.', 'slicewp' ); ?></label>

		</div><!-- / Allow Affiliates Registration -->
		
		<!-- Register new Affiliates with Active status -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-affiliate-register-status-active">
					<?php echo __( 'Register Affiliates as Active', 'slicewp' ); ?>
				</label>
			</div>

			<div class="slicewp-switch">

				<input id="slicewp-affiliate-register-status-active" class="slicewp-toggle slicewp-toggle-round" name="settings[affiliate_register_status_active]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['affiliate_register_status_active'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_register_status_active' ) : '' ), '1' ); ?> />
				<label for="slicewp-affiliate-register-status-active"></label>

			</div>

			<label for="slicewp-affiliate-register-status-active"><?php echo __( 'New affiliate accounts will be created with Active status.', 'slicewp' ); ?></label>

		</div><!-- / Register new Affiliates with Active status -->

		<!-- Auto Register Affiliates -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-auto-register-affiliates">
					<?php echo __( 'Auto Register Affiliates', 'slicewp' ); ?>
				</label>
			</div>

			<div class="slicewp-switch">

				<input id="slicewp-auto-register-affiliates" class="slicewp-toggle slicewp-toggle-round" name="settings[affiliate_auto_register]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['affiliate_auto_register'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_auto_register' ) : '' ), '1' ); ?> />
				<label for="slicewp-auto-register-affiliates"></label>

			</div>

			<label for="slicewp-auto-register-affiliates"><?php echo __( 'Automatically register new user accounts as affiliates.', 'slicewp' ); ?></label>

		</div>
		<!-- / Auto Register Affiliates -->

        <!-- Required registration fields -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label>
					<?php echo __( 'Required Affiliate Fields', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'Set which fields should be required for the affiliate to complete on the registration and affiliate account pages.', 'slicewp' ) ); ?>
				</label>
			</div>

			<div style="margin-bottom: 10px;">

				<div class="slicewp-switch">

					<input id="slicewp-required-field-payment-email" class="slicewp-toggle slicewp-toggle-round" name="settings[required_field_payment_email]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['required_field_payment_email'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'required_field_payment_email' ) : '' ), '1' ); ?> />
					<label for="slicewp-required-field-payment-email"></label>

				</div>

				<label for="slicewp-required-field-payment-email"><?php echo __( 'Payment Email', 'slicewp' ); ?></label>							

			</div>

			<div style="margin-bottom: 10px;">

				<div class="slicewp-switch">

					<input id="slicewp-required-field-website" class="slicewp-toggle slicewp-toggle-round" name="settings[required_field_website]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['required_field_website'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'required_field_website' ) : '' ), '1' ); ?> />
					<label for="slicewp-required-field-website"></label>

				</div>

				<label for="slicewp-required-field-website"><?php echo __( 'Website', 'slicewp' ); ?></label>							
			
			</div>

			<div>

				<div class="slicewp-switch">

					<input id="slicewp-required-field-promotional-methods" class="slicewp-toggle slicewp-toggle-round" name="settings[required_field_promotional_methods]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['required_field_promotional_methods'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'required_field_promotional_methods' ) : '' ), '1' ); ?> />
					<label for="slicewp-required-field-promotional-methods"></label>

				</div>

				<label for="slicewp-required-field-promotional-methods"><?php echo __( 'How will you promote us?', 'slicewp' ); ?></label>							
			
			</div>

		</div>
		<!-- / Required registration fields -->

	</div>

</div><!-- / Registration -->

<!-- Referral Tracking -->
<div class="slicewp-card">

	<?php $affiliate_keyword = ( ! empty( $_POST['settings']['affiliate_keyword'] ) ? $_POST['settings']['affiliate_keyword'] : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_keyword' ) : '' ) ); ?>

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'Referral Tracking', 'slicewp' ); ?></span>

		<div class="slicewp-card-actions">
			<a href="https://slicewp.com/docs/affiliate-links/" target="_blank" class="slicewp-button-info" title="<?php echo esc_attr( __( 'Click to learn more...', 'slicewp' ) ); ?>"><svg height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path></g></svg></a>
		</div>
	</div>

	<div class="slicewp-card-inner">

		<!-- Affiliate Keyword -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-affiliate-keyword">
					<?php echo __( 'Affiliate Keyword', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( '<p>' . __( 'The URL query parameter name used for affiliate identification.', 'slicewp' ) . '</p><p>' . sprintf( __( 'Example: %s', 'slicewp' ), '<code style="font-family: inherit;">' . trailingslashit( site_url() ) . '?' . '<strong>' . esc_html( $affiliate_keyword ) . '</strong>' . '=' . $affiliate_id ) . '</code>' . '</p>' . '<hr />' . '<a href="https://slicewp.com/docs/affiliate-links/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<input id="slicewp-affiliate-keyword" name="settings[affiliate_keyword]" type="text" value="<?php echo esc_attr( ! empty( $_POST['settings']['affiliate_keyword'] ) ? $_POST['settings']['affiliate_keyword'] : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_keyword' ) : '' ) ); ?>">

		</div><!-- / Affiliate Keyword -->

		<!-- Cookie Duration -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-cookie-duration">
					<?php echo __( 'Tracking Cookie Duration', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( '<p>' . __( 'The number of days a referred visitor is being tracked.' , 'slicewp' ) . '</p><p>' . __( 'If the referred visitor makes a purchase in this timeframe, the referring affiliate will be rewarded a commission.', 'slicewp' ) . '</p>' . '<hr />' . '<a href="https://slicewp.com/docs/cookie-duration/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<div style="display: flex; gap: 10px;">
				<input id="slicewp-cookie-duration" name="settings[cookie_duration]" type="number" value="<?php echo ( ! empty( $_POST['settings']['cookie_duration'] ) ? esc_attr( $_POST['settings']['cookie_duration'] ) : ( slicewp_get_setting( 'cookie_duration' ) ) ); ?>">
				<input type="text" value="<?php echo __( 'Days', 'slicewp' ); ?>" disabled />
			</div>

		</div><!-- / Cookie Duration -->

		<!-- Credit First/Last Affiliate -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

			<div class="slicewp-field-label-wrapper">
				<label>
					<?php echo __( 'Affiliate Attribution', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( '<p>' . __( 'Sets which affiliate is rewarded the commission when a visitor clicks more than one affiliate\'s referral link before converting.', 'slicewp' ) . '</p>' . '<p>' . sprintf( __( '%1$sFirst Affiliate%2$s - the affiliate whose link was clicked first keeps the credit. Their tracking cookie stays in place until it expires, even if the visitor later clicks another affiliate\'s link.', 'slicewp' ), '<strong>', '</strong>' ) . '</p>' . '<p>' . sprintf( __( '%1$sLast Affiliate%2$s - the affiliate whose link was clicked last gets the credit, replacing the previous tracking cookie.', 'slicewp' ), '<strong>', '</strong>' ) . '</p>' . '<hr /><a href="https://slicewp.com/docs/referral-tracking/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<select id="slicewp-affiliate-credit" name="settings[affiliate_credit]" class="slicewp-select2">
				<option value="first" <?php echo selected( ( ! empty( $_POST['settings']['affiliate_credit'] ) ? $_POST['settings']['affiliate_credit'] : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_credit' ) : '' ) ) , 'first' ); ?>><?php echo __( 'First Affiliate', 'slicewp' ); ?></option>
				<option value="last" <?php echo selected( ( ! empty( $_POST['settings']['affiliate_credit'] ) ? $_POST['settings']['affiliate_credit'] : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_credit' ) : '' ) ) , 'last' ); ?>><?php echo __( 'Last Affiliate', 'slicewp' ); ?></option>
			</select>

		</div><!-- / Credit First/Last Affiliate -->

        <!-- Friendly Affiliate URLs -->
        <div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

            <div class="slicewp-field-label-wrapper">
                <label for="slicewp-friendly-affiliate-urls">
                    <?php echo __( 'Friendly Affiliate URLs', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( '<p>' . __( 'When enabled, the affiliate referral links will look like this:', 'slicewp' ) . '<br />' . '<code style="display: inline-block; margin-top: 3px; font-family: inherit;">' . untrailingslashit( site_url() ) . '<strong>' . '/' . '<span>' . esc_html( $affiliate_keyword ) . '</span>' . '/' . $affiliate_id . '/' . '</strong>' . '</code>' . '</p><p>' . __( 'Instead of this:', 'slicewp' ) . '<br />' . '<code style="display: inline-block; margin-top: 3px; font-family: inherit;">' . untrailingslashit( site_url() ) . '<strong>' . '/?' . '<span>' . esc_html( $affiliate_keyword ) . '</span>' . '=' . $affiliate_id . '</strong>' . '</code>' . '</p>' . '<hr />' . '<a href="https://slicewp.com/docs/affiliate-links/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
                </label>
            </div>

            <div class="slicewp-switch">

                <input id="slicewp-friendly-affiliate-urls" class="slicewp-toggle slicewp-toggle-round" name="settings[friendly_affiliate_url]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['friendly_affiliate_url'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'friendly_affiliate_url' ) : '' ), '1' ); ?> />
                <label for="slicewp-friendly-affiliate-urls"></label>

            </div>

            <label for="slicewp-friendly-affiliate-urls"><?php echo __( 'Use friendly affiliate URLs.', 'slicewp' ); ?></label>

        </div><!-- / Friendly Affiliate URLs -->

		<!-- Referral Links QR Code -->
        <div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

            <div class="slicewp-field-label-wrapper">
                <label for="slicewp-referral-link-qr-code">
                    <?php echo __( 'Affiliate Link QR Code', 'slicewp' ); ?>
                    <?php echo slicewp_output_tooltip( __( 'When enabled, your affiliates will have the option to view and download the QR code for their affiliate referral links.', 'slicewp' ) ); ?>
                </label>
            </div>

            <div class="slicewp-switch">

                <input id="slicewp-referral-link-qr-code" class="slicewp-toggle slicewp-toggle-round" name="settings[referral_link_qr_code]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['referral_link_qr_code'] ) ? '1' : ( empty( $_POST ) ? slicewp_get_setting( 'referral_link_qr_code' ) : '' ), '1' ); ?> />
                <label for="slicewp-referral-link-qr-code"></label>

            </div>

            <label for="slicewp-referral-link-qr-code"><?php echo __( 'Show QR code for affiliate URLs in the affiliate account.', 'slicewp' ); ?></label>

        </div><!-- Referral Links QR Code -->

		<!-- Affiliate Default URL Override -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-affiliate-referral-url">
					<?php echo __( 'Default URL Override', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( "The default affiliate referral URL shown in the affiliate account. If left empty, your website's home URL will be used.", 'slicewp' ) ); ?>
				</label>
			</div>

			<input id="slicewp-affiliate-referral-url" name="settings[affiliate_url_base]" type="url" value="<?php echo esc_attr( ! empty( $_POST['settings']['affiliate_url_base'] ) ? $_POST['settings']['affiliate_url_base'] : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_url_base' ) : '' ) ); ?>">
			
		</div><!-- / Affiliate Default URL Override -->

	</div>

</div><!-- / Referral Tracking -->

<!-- Pages -->
<div class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'Pages', 'slicewp' ); ?></span>

		<div class="slicewp-card-actions">
			<a href="https://slicewp.com/docs/category/affiliate-area/" target="_blank" class="slicewp-button-info" title="<?php echo esc_attr( __( 'Click to learn more...', 'slicewp' ) ); ?>"><svg height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path></g></svg></a>
		</div>
	</div>

	<div class="slicewp-card-inner">

		<!-- Affiliate Account Page -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-affiliate-account-page">
					<?php echo __( 'Affiliate Account Page', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( "Select the page you wish to be your affiliates' private area.", 'slicewp' ) . '<hr />' . '<a href="https://slicewp.com/docs/adding-an-affiliate-account-page/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<select id="slicewp-affiliate-account-page" name="settings[page_affiliate_account]" class="slicewp-select2">
				<option value=""><?php echo( __( 'Select...', 'slicewp' ) ); ?></option>

				<?php

					$pages = get_pages();

					foreach ( $pages as $page ) {
						echo '<option value="' . absint( $page->ID ) . '"' . selected( ! empty( $_POST['settings']['page_affiliate_account'] ) ? absint( $_POST['settings']['page_affiliate_account'] ) : ( empty( $_POST ) ? slicewp_get_setting( 'page_affiliate_account' ) : '' ), $page->ID, false ) . '>' . esc_html( $page->post_title ) . '</option>';
					}

				?>
			</select>

		</div><!-- / Affiliate Account Page -->

		<!-- Affiliate Registration Page -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-affiliate-register-page">
					<?php echo __( 'Affiliate Registration Page', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( "Select the page where your visitors can register for your affiliate program.", 'slicewp' ) . '<hr />' . '<a href="https://slicewp.com/docs/adding-affiliate-registration-page/" target="_blank">' . __( 'Click here to learn more', 'slicewp' ) . '</a>' ); ?>
				</label>
			</div>

			<select id="slicewp-affiliate-register-page" name="settings[page_affiliate_register]" class="slicewp-select2">
				<option value=""><?php echo( __( 'Select...', 'slicewp' ) ); ?></option>
				
				<?php

					$pages = get_pages();

					foreach ( $pages as $page ) {
						echo '<option value="' . absint( $page->ID ) . '"' . selected( ! empty( $_POST['settings']['page_affiliate_register'] ) ? absint( $_POST['settings']['page_affiliate_register'] ) : ( empty( $_POST ) ? slicewp_get_setting( 'page_affiliate_register' ) : '' ), $page->ID, false ) . '>' . esc_html( $page->post_title ) . '</option>';
					}

				?>
			</select>

		</div><!-- / Affiliate Registration Page -->

		<!-- Reset Password Page -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-reset-password-page">
					<?php echo __( 'Reset Password Page', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'Select the page where your affiliates can reset their password in case they lost it.', 'slicewp' ) ); ?>
				</label>
			</div>

			<select id="slicewp-reset-password-page" name="settings[page_affiliate_reset_password]" class="slicewp-select2">
				<option value=""><?php echo( __( 'Select...', 'slicewp' ) ); ?></option>

				<?php

					$pages = get_pages();

					foreach ( $pages as $page ) {
						echo '<option value="' . absint( $page->ID ) . '"' . selected( ! empty( $_POST['settings']['page_affiliate_reset_password'] ) ? absint( $_POST['settings']['page_affiliate_reset_password'] ) : ( empty( $_POST ) ? slicewp_get_setting( 'page_affiliate_reset_password' ) : '' ), $page->ID, false ) . '>' . esc_html( $page->post_title ) . '</option>';
					}

				?>
			</select>

		</div><!-- / Reset Password Page -->

		<!-- Terms and Conditions Page -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-terms-conditions-page">
					<?php echo __( 'Terms and Conditions Page', 'slicewp' ); ?>
				</label>
			</div>

			<select id="slicewp-terms-conditions-page" name="settings[page_terms_conditions]" class="slicewp-select2">
				<option value=""><?php echo( __( 'Select...', 'slicewp' ) ); ?></option>

				<?php

					$pages = get_pages();

					foreach ( $pages as $page ) {
						echo '<option value="' . absint( $page->ID ) . '"' . selected( ! empty( $_POST['settings']['page_terms_conditions'] ) ? absint( $_POST['settings']['page_terms_conditions'] ) : ( empty( $_POST ) ? slicewp_get_setting( 'page_terms_conditions' ) : '' ), $page->ID, false ) . '>' . esc_html( $page->post_title ) . '</option>';
					}

				?>
			</select>

		</div><!-- / Terms and Conditions Page -->

		<!-- Terms and Conditions Checkbox -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-terms-label">
					<?php echo __( 'Terms and Conditions Label', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'This label will acompanion the Terms and Conditions checkbox.', 'slicewp' ) ); ?>
				</label>
			</div>

			<input id="slicewp-terms-label" name="settings[terms_label]" type="text" value="<?php echo esc_attr( ! empty( $_POST['settings']['terms_label'] ) ? $_POST['settings']['terms_label'] : ( empty( $_POST ) ? slicewp_get_setting( 'terms_label' ) : '' ) ); ?>">
			
		</div><!-- / Terms and Conditions Checkbox -->

	</div>

</div><!-- / Pages Settings -->

<!-- Account Settings -->
<div class="slicewp-card">

	<div class="slicewp-card-header">
		<span class="slicewp-card-title"><?php echo __( 'Account Settings', 'slicewp' ); ?></span>
	</div>

	<div class="slicewp-card-inner">

		<!-- Affiliate Account Logout -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

			<div class="slicewp-field-label-wrapper">
				<label for="slicewp-affiliate-account-logout">
					<?php echo __( 'Affiliate Account Logout', 'slicewp' ); ?>
				</label>
			</div>

			<div class="slicewp-switch">

				<input id="slicewp-affiliate-account-logout" class="slicewp-toggle slicewp-toggle-round" name="settings[affiliate_account_logout]" type="checkbox" value="1" <?php checked( ! empty( $_POST['settings']['affiliate_account_logout'] ) ? esc_attr( $_POST['settings']['affiliate_account_logout'] ) : ( empty( $_POST ) ? slicewp_get_setting( 'affiliate_account_logout' ) : '' ), '1' ); ?> />
				<label for="slicewp-affiliate-account-logout"></label>

			</div>

			<label for="slicewp-affiliate-account-logout"><?php echo __( 'Enable to add a logout link in the affiliate account page.', 'slicewp' ); ?></label>

		</div><!-- Affiliate Account Logout -->

		<!-- Commission Statuses Display -->
		<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide slicewp-last">

			<?php

				/**
				 * @todo - Add a link to the documentation article in the tooltip, explaining this setting and it's customizability features.
				 * 
				 */

			?>
			<div class="slicewp-field-label-wrapper">
				<label>
					<?php echo __( 'Commission Statuses Display', 'slicewp' ); ?>
					<?php echo slicewp_output_tooltip( __( 'Choose the commission statuses to display in the commissions table of the affiliate account page.', 'slicewp' ) ); ?>
				</label>
			</div>

			<?php
				$commission_statuses 		 = slicewp_get_commission_available_statuses();
				$account_commission_statuses = slicewp_get_setting( 'affiliate_account_commission_statuses', array() );
			?>

			<?php foreach ( $commission_statuses as $status_slug => $status_name ): ?>

				<div <?php echo ( $status_slug != key( array_slice( $commission_statuses, -1, 1, true) ) ? 'style="margin-bottom: 10px;"' : '' ); ?>>

					<?php $is_disabled = ( in_array( $status_slug, array( 'paid', 'unpaid' ) ) ? true : false ); ?>
					<?php $is_checked  = ( in_array( $status_slug, array( 'paid', 'unpaid' ) ) ? true : ( ! empty( $_POST['settings']['affiliate_account_commission_statuses'] ) && in_array( $status_slug, $_POST['settings']['affiliate_account_commission_statuses'] ) ? true : ( empty( $_POST ) ? ( in_array( $status_slug, $account_commission_statuses ) ? true : false ) : false ) ) ); ?>

					<div class="slicewp-switch <?php echo ( $is_disabled ? 'slicewp-disabled' : '' ); ?>" <?php echo ( $is_disabled ? 'title="' . __( 'This commission status will always be shown to affiliates.', 'slicewp' ) . '"' : '' ); ?>>

						<input id="slicewp-commission-status-<?php echo esc_attr( $status_slug ); ?>" class="slicewp-toggle slicewp-toggle-round" name="settings[affiliate_account_commission_statuses][]" type="checkbox" value="<?php echo esc_attr( $status_slug ); ?>" <?php echo ( $is_checked ? 'checked' : '' ); ?> <?php echo ( $is_disabled ? 'disabled' : '' ); ?> />
						<label for="slicewp-commission-status-<?php echo esc_attr( $status_slug ); ?>"></label>

					</div>

					<label for="slicewp-commission-status-<?php echo esc_attr( $status_slug ); ?>"><?php echo esc_html( $status_name ); ?></label>							

				</div>
				
			<?php endforeach; ?>

		</div>
		<!-- / Commission Statuses Display -->

	</div>

</div><!-- / Account Settings -->

<?php

	/**
	 * Hook to add extra cards if needed to the Affiliate Area tab.
	 *
	 */
	do_action( 'slicewp_view_settings_tab_affiliate_area_bottom' );

?>

<!-- Save Settings Button -->
<input type="submit" class="slicewp-form-submit slicewp-button-primary" value="<?php echo __( 'Save Settings', 'slicewp' ); ?>" />