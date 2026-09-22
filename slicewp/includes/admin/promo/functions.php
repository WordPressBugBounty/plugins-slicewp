<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the admin area.
 *
 */
function slicewp_include_files_admin_promo() {

	// Get dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include the reports promo page.
	if ( file_exists( $dir_path . 'class-submenu-page-promo-reports.php' ) ) {
        include $dir_path . 'class-submenu-page-promo-reports.php';
    }

}
add_action( 'slicewp_include_files', 'slicewp_include_files_admin_promo' );


/**
 * Register the promo reports admin submenu page.
 *
 */
function slicewp_register_submenu_page_promo_reports( $submenu_pages ) {

    if ( slicewp_is_website_registered() ) {
        return $submenu_pages;
    }

	if ( slicewp_add_ons_exist() ) {
        return $submenu_pages;
    }

	if ( class_exists( 'SliceWP_Pro' ) ) {
		return $submenu_pages;
	}

	if ( ! is_array( $submenu_pages ) ) {
        return $submenu_pages;
    }

	$submenu_pages['reports'] = array(
		'class_name' => 'SliceWP_Submenu_Page_Promo_Reports',
		'data' 		 => array(
			'page_title' => __( 'Reports', 'slicewp' ),
			'menu_title' => __( 'Reports', 'slicewp' ),
			'capability' => apply_filters( 'slicewp_submenu_page_capability_promo_reports', 'manage_options' ),
			'menu_slug'  => 'slicewp-promo-reports'
		)
	);

	return $submenu_pages;

}
add_filter( 'slicewp_register_submenu_page', 'slicewp_register_submenu_page_promo_reports', 40 );


/**
 * Adds a call-to-action at the bottom of pages that have list tables
 *
 */
function slicewp_promo_add_upgrade_card_cta() {

	if ( slicewp_is_website_registered() ) {
		return;
	}

	if ( class_exists( 'SliceWP_Pro' ) ) {
		return;
	}

	?>

	<a id="slicewp-upgrade-card-cta" href="<?php echo add_query_arg( array( 'page' => 'slicewp-add-ons' ), 'admin.php' ); ?>">

		<div class="slicewp-card">
			<div class="slicewp-card-inner">
				<p><?php echo __( 'Missing anything? Discover more powerful features in the premium version now!', 'slicewp' ); ?></p>
				<span><?php echo __( "I'm interested", 'slicewp' ); ?></span>
			</div>
		</div>

	</a>

	<?php

}
add_action( 'slicewp_view_affiliates_bottom', 'slicewp_promo_add_upgrade_card_cta' );
add_action( 'slicewp_view_commissions_bottom', 'slicewp_promo_add_upgrade_card_cta' );
add_action( 'slicewp_view_creatives_bottom', 'slicewp_promo_add_upgrade_card_cta' );
add_action( 'slicewp_view_visits_bottom', 'slicewp_promo_add_upgrade_card_cta' );
add_action( 'slicewp_view_payouts_bottom', 'slicewp_promo_add_upgrade_card_cta' );


/**
 * Include the promo commission rates view for the affiliate.
 *
 */
function slicewp_promo_view_affiliates_add_affiliate_bottom_affiliate_commission_rates() {

	if ( slicewp_is_website_registered() ) {
		return;
	}

	if ( slicewp_add_ons_exist() ) {
		return;
	}

	if ( class_exists( 'SliceWP_Pro' ) ) {
		return;
	}

	$affiliate_id = ( ! empty( $_GET['affiliate_id'] ) ? sanitize_text_field( $_GET['affiliate_id'] ) : 0 );

	?>

	<div class="slicewp-card slicewp-card-promo">

		<div class="slicewp-card-header">

			<span class="slicewp-card-title"><?php echo __( 'Affiliate Commission Rates', 'slicewp' ); ?></span>

			<div class="slicewp-card-actions">
				<a class="slicewp-promo-pill" href="https://slicewp.com/" target="_blank"><?php echo __( 'Pro Feature', 'slicewp' ); ?></a>
			</div>

		</div>

		<div class="slicewp-card-inner">

			<!-- Enable Custom Commission Rates -->
			<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

				<div class="slicewp-field-label-wrapper">
					<label><?php echo __( 'Commission Rates', 'slicewp' ); ?></label>
				</div>
				
				<div class="slicewp-switch">

					<input class="slicewp-toggle slicewp-toggle-round" disabled type="checkbox" value="1" checked />
					<label></label>

				</div>

				<label><?php echo __( 'Enable custom commission rates for this affiliate.', 'slicewp' ); ?></label>

			</div>
			<!-- / Enable Custom Commission Rates -->

			<!-- Commissions Rates -->
			<?php 
				$commission_types = slicewp_get_available_commission_types( true );
				$count = 0;
			?>

			<?php foreach ( $commission_types as $type => $details ): ?>

				<?php if ( in_array( $type, array( 'recurring', 'lifetime_sale', 'product_revenue_share' ) ) ) continue; ?>

				<?php
					$rate 	   = slicewp_get_affiliate_meta( $affiliate_id, 'commission_rate_' . $type, true );
					$rate_type = slicewp_get_affiliate_meta( $affiliate_id, 'commission_rate_type_' . $type, true );
				?>

				<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-field-wrapper-commission-rate">

					<div class="slicewp-field-label-wrapper">
						<label for="slicewp-commission-rate-<?php echo str_replace( '_', '-', $type ); ?>">
							<?php echo sprintf( __( '%s rate', 'slicewp' ), $details['label'] ); ?>
						</label>
					</div>
					
					<input id="slicewp-commission-rate-<?php echo str_replace( '_', '-', $type ); ?>" type="text" value="25" disabled />					

					<select name="commission_rate_type_<?php echo $type; ?>" class="slicewp-select2" disabled>
						<?php foreach( $details['rate_types'] as $details_rate_type ): ?>
							<option value="<?php echo esc_attr( $details_rate_type ); ?>" <?php selected( $rate_type, $details_rate_type ); ?>><?php echo ( $details_rate_type == 'percentage' ? __( 'Percentage (%)', 'slicewp' ) : __( 'Fixed Amount', 'slicewp' ) ); ?></option>
						<?php endforeach; ?>
					</select>

				</div>

				<?php $count++; ?>

			<?php endforeach; ?>
			<!-- / Commisions Rates -->

		</div>

	</div>

	<?php

}
add_action( 'slicewp_view_affiliates_add_affiliate_bottom', 'slicewp_promo_view_affiliates_add_affiliate_bottom_affiliate_commission_rates' );
add_action( 'slicewp_view_affiliates_edit_affiliate_bottom', 'slicewp_promo_view_affiliates_add_affiliate_bottom_affiliate_commission_rates' );


/**
 * Outputs the "Affiliate Fields" promo in the "Affiliate Area" tab of the "Settings" page.
 *
 */
function slicewp_promo_view_settings_tab_affiliate_area_bottom_affiliate_fields() {

	if ( slicewp_is_website_registered() ) {
		return;
	}

	if ( class_exists( 'SliceWP_Pro' ) ) {
		return;
	}

	?>

	<style>
		.slicewp-promo-tagline { margin: 0 0 16px; font-weight: 500; color: #2e4453; }
		.slicewp-promo-features { margin: 0; padding: 0; list-style: none; }
		.slicewp-promo-features li { position: relative; padding: 10px 0 5px 26px; color: #444; margin-top: 5px !important; }
		.slicewp-promo-features li + li { border-top: 1px solid rgba(200, 215, 225, 0.4); }
		.slicewp-promo-features li::before { content: ''; position: absolute; left: 0; top: 50%; margin-top: -5px; width: 16px; height: 16px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%2316a085'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'/%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; background-position: center; }
	</style>

	<div class="slicewp-card slicewp-card-promo">

		<div class="slicewp-card-header">

			<span class="slicewp-card-title"><?php echo __( 'Affiliate Fields', 'slicewp' ); ?></span>

			<div class="slicewp-card-actions">
				<a class="slicewp-promo-pill" href="https://slicewp.com/products/custom-affiliate-fields/" target="_blank"><?php echo __( 'Pro Feature', 'slicewp' ); ?></a>
			</div>

		</div>

		<div class="slicewp-card-inner">

			<p class="slicewp-promo-tagline"><?php echo __( 'Gather exactly the information your affiliate program needs.', 'slicewp' ); ?></p>

			<ul class="slicewp-promo-features">
				<li><?php echo __( 'Add text fields, checkboxes, radio buttons, dropdowns and more to your forms.', 'slicewp' ); ?></li>
				<li><?php echo __( 'Choose where each field appears: registration form, affiliate account or admin-only.', 'slicewp' ); ?></li>
				<li><?php echo __( 'Mark fields as required or optional and add labels, placeholders and descriptions.', 'slicewp' ); ?></li>
				<li><?php echo __( 'Reorder fields by dragging them to build the perfect affiliate onboarding flow.', 'slicewp' ); ?></li>
			</ul>

		</div>

		<div class="slicewp-card-footer">
			<a class="slicewp-button-secondary" href="https://slicewp.com/products/custom-affiliate-fields/" target="_blank"><?php echo __( 'Learn More', 'slicewp' ); ?></a>
		</div>

	</div>

	<?php

}
add_action( 'slicewp_view_settings_tab_affiliate_area_bottom', 'slicewp_promo_view_settings_tab_affiliate_area_bottom_affiliate_fields' );


/**
 * Outputs the "Commission Types" promo in the "Commissions" tab of the "Settings" page.
 *
 */
function slicewp_promo_view_settings_tab_commissions_bottom_commission_types() {

	if ( slicewp_is_website_registered() ) {
		return;
	}

	if ( class_exists( 'SliceWP_Pro' ) ) {
		return;
	}

	?>

	<style>
		.slicewp-promo-tagline { margin: 0 0 16px; font-weight: 500; color: #2e4453; }
		.slicewp-promo-features { margin: 0; padding: 0; list-style: none; }
		.slicewp-promo-features li { position: relative; padding: 10px 0 5px 26px; color: #444; margin-top: 5px !important; }
		.slicewp-promo-features li + li { border-top: 1px solid rgba(200, 215, 225, 0.4); }
		.slicewp-promo-features li::before { content: ''; position: absolute; left: 0; top: 50%; margin-top: -5px; width: 16px; height: 16px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%2316a085'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'/%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; background-position: center; }
	</style>

	<div class="slicewp-card slicewp-card-promo">

		<div class="slicewp-card-header">

			<span class="slicewp-card-title"><?php echo __( 'More Commission Types', 'slicewp' ); ?></span>

			<div class="slicewp-card-actions">
				<a class="slicewp-promo-pill" href="https://slicewp.com/add-ons/" target="_blank"><?php echo __( 'Pro Feature', 'slicewp' ); ?></a>
			</div>

		</div>

		<div class="slicewp-card-inner">

			<p class="slicewp-promo-tagline"><?php echo __( 'Unlock powerful commission models that go beyond a flat rate and keep affiliates motivated.', 'slicewp' ); ?></p>

			<ul class="slicewp-promo-features">
				<li><?php echo __( 'Recurring Commissions — automatically reward affiliates for every subscription renewal, not just the first sale.', 'slicewp' ); ?></li>
				<li><?php echo __( 'Lifetime Commissions — tie customers to affiliates permanently and pay a commission on every future purchase they make.', 'slicewp' ); ?></li>
				<li><?php echo __( 'Lead Commissions — pay affiliates for form submissions and leads, not just completed orders.', 'slicewp' ); ?></li>
				<li><?php echo __( 'Performance Bonuses — automatically reward top performers when they hit defined sales or referral targets.', 'slicewp' ); ?></li>
			</ul>

		</div>

		<div class="slicewp-card-footer">
			<a class="slicewp-button-secondary" href="https://slicewp.com/add-ons/" target="_blank"><?php echo __( 'View All Add-ons', 'slicewp' ); ?></a>
		</div>

	</div>

	<?php

}
// add_action( 'slicewp_view_settings_tab_commissions_bottom', 'slicewp_promo_view_settings_tab_commissions_bottom_commission_types' );


/**
 * Registers a notice to review SliceWP.
 *
 */
function slicewp_admin_notice_review_request() {

	if ( empty( $_GET['page'] ) || ! is_string( $_GET['page'] ) ) {
		return;
	}

	if ( false === strpos( $_GET['page'], 'slicewp' ) ) {
		return;
	}

	if ( $_GET['page'] == 'slicewp-setup' ) {
		return;
	}

	if ( ( (int)slicewp_get_option( 'first_activation' ) + 21 * DAY_IN_SECONDS ) > time() ) {
		return;
	}

	// Check if the user dismissed the notice, show it only once every two weeks
	$review_request = slicewp_get_option( 'review_request', array() );

	if ( isset( $review_request['dismissed_temp'] ) && empty( $review_request['dismissed_temp'] ) ) {
        return;
    }

	if ( isset( $review_request['dismissed_temp'] ) && isset( $review_request['dismissed_time'] ) && ( $review_request['dismissed_time'] + 7 * DAY_IN_SECONDS ) > time() ) {
        return;
    }

	?>

		<div class="notice notice-info">
			<p><?php esc_html_e( "Hey, I noticed you've been using SliceWP for a few weeks now - that’s awesome! Could you please do me a BIG favor and give the plugin a 5-star rating on WordPress to help us spread the word? It would mean the world to us!", 'slicewp' ); ?></p>
			<p><strong><?php esc_html_e( '~ Iova Mihai, SliceWP co-founder', 'slicewp' ); ?></strong></p>
			<p>
				<a href="https://wordpress.org/support/plugin/slicewp/reviews/?filter=5#new-post" target="_blank" rel="noopener noreferrer" style="display: inline-block; margin-bottom: 3px;"><?php esc_html_e( 'Ok, you deserve it', 'slicewp' ); ?></a><br />
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'slicewp_action' => 'dismiss_notice_review_request', 'temp' => 1 ) ), 'slicewp_dismiss_notice_review_request', 'slicewp_token' ) ); ?>" rel="noopener noreferrer" style="display: inline-block; margin-bottom: 5px;"><?php esc_html_e( 'Nope, maybe later', 'slicewp' ); ?></a><br />
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'slicewp_action' => 'dismiss_notice_review_request', 'temp' => 0 ) ), 'slicewp_dismiss_notice_review_request', 'slicewp_token' ) ); ?>" rel="noopener noreferrer" style="display: inline-block; margin-bottom: 5px;"><?php esc_html_e( 'I already did', 'slicewp' ); ?></a>
			</p>
		</div>

	<?php

}
add_action( 'admin_notices', 'slicewp_admin_notice_review_request' );


/**
 * Handles the dismissal of the review request admin notice
 *
 */
function slicewp_admin_action_dismiss_notice_review_request() {

	// Verify for nonce
	if ( empty( $_GET['slicewp_token'] ) || ! wp_verify_nonce( $_GET['slicewp_token'], 'slicewp_dismiss_notice_review_request' ) ) {
        return;
    }

	if ( ! isset( $_GET['temp'] ) ) {
        return;
    }

	$review_request = array(
		'dismissed_temp' => absint( $_GET['temp'] ),
		'dismissed_time' => time()
	);

	update_option( 'slicewp_review_request', $review_request );

	// Redirect to the current page
	wp_redirect( remove_query_arg( array( 'slicewp_action', 'slicewp_token', 'temp' ) ) );
	exit;

}
add_action( 'slicewp_admin_action_dismiss_notice_review_request', 'slicewp_admin_action_dismiss_notice_review_request' );


/**
 * Outputs pro payout method upsell items at the bottom of the Payout Methods settings card.
 * Skips any method that is already registered (e.g. when SliceWP Pro is active).
 *
 */
function slicewp_view_settings_tab_payouts_payout_methods_upsell() {

	if ( slicewp_is_website_registered() ) {
		return;
	}

	if ( class_exists( 'SliceWP_Pro' ) ) {
		return;
	}

	$upsell_methods = array(
		'paypal_payouts' => array(
			'label'    => __( 'PayPal Payouts', 'slicewp' ),
			'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="#002991" d="M 5.028,0.038 C 5.028,0.062 2.066,19.101 2.055,19.156 L 2.044,19.204 L 4.524,19.204 L 7.006,19.204 L 7.569,15.615 L 8.13,12.024 L 10.396,12.015 C 12.873,12.004 12.851,12.007 13.536,11.867 C 14.769,11.62 15.896,11.039 16.842,10.164 C 18.02,9.074 18.811,7.576 18.983,6.108 C 19.021,5.777 19.028,5.198 18.992,4.888 C 18.86,3.664 18.294,2.522 17.392,1.662 C 16.438,0.749 15.162,0.186 13.737,0.044 C 13.476,0.02 5.028,0.011 5.028,0.038"/><path fill="#60CDFF" d="M 9.262,4.877 C 9.255,4.91 8.747,8.146 8.133,12.066 C 7.262,17.618 6.435,22.782 6.232,23.929 L 6.219,24 L 8.69,24 L 11.162,24 L 11.733,20.449 C 12.046,18.493 12.307,16.88 12.312,16.857 C 12.32,16.824 12.46,16.822 13.962,16.811 C 15.697,16.8 15.83,16.793 16.387,16.683 C 18.833,16.206 20.811,14.491 21.6,12.161 C 22.117,10.641 22.055,9.246 21.417,7.945 C 20.608,6.301 19.028,5.209 16.972,4.88 L 16.663,4.831 L 12.968,4.824 L 9.275,4.815 L 9.262,4.877"/><path fill="#008CFF" d="M18.983,6.108c-0.172,1.468 -0.963,2.966 -2.141,4.056c-0.946,0.875 -2.073,1.456 -3.306,1.703c-0.685,0.14 -0.663,0.137 -3.14,0.148l-2.256,0.009c0.611,-3.901 1.115,-7.114 1.122,-7.147l0.013,-0.062l3.693,0.009l3.695,0.007l0.309,0.049c0.749,0.12 1.434,0.341 2.043,0.653c-0.003,0.211 -0.014,0.42 -0.032,0.575zM7.006,19.204h-0.003c0.051,-0.316 0.103,-0.643 0.156,-0.98z"/></svg>',
			'supports' => array( 'single_payment', 'bulk_payments', 'payout_request_invoice', 'integrated_payment_processing' ),
		),
		'stripe' => array(
			'label'    => __( 'Stripe', 'slicewp' ),
			'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="3" fill="#533AFD"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5.625 18.375L18.375 15.6711V5.625L5.625 8.3595V18.375Z" fill="white"/></svg>',
			'supports' => array( 'single_payment', 'bulk_payments', 'payout_request_invoice', 'integrated_payment_processing' ),
		),
		'store_credit' => array(
			'label'    => __( 'Store Credit', 'slicewp' ),
			'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M128 96C92.7 96 64 124.7 64 160L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 256C576 220.7 547.3 192 512 192L136 192C122.7 192 112 181.3 112 168C112 154.7 122.7 144 136 144L520 144C533.3 144 544 133.3 544 120C544 106.7 533.3 96 520 96L128 96zM480 320C497.7 320 512 334.3 512 352C512 369.7 497.7 384 480 384C462.3 384 448 369.7 448 352C448 334.3 462.3 320 480 320z"/></svg>',
			'supports' => array( 'single_payment', 'bulk_payments', 'integrated_payment_processing' ),
		)
	);

	foreach ( $upsell_methods as $method_slug => $method_data ) {

		?>

			<div class="slicewp-expandable-item slicewp-payout-method-upsell">

				<div class="slicewp-expandable-item-header">

					<div>
						<?php if ( ! empty( $method_data['icon'] ) ) echo '<div class="slicewp-payout-method-icon">' . wp_kses( $method_data['icon'], slicewp_get_kses_allowed_html() ) . '</div>'; ?>
					</div>

					<div>

						<label>
							<?php echo esc_html( $method_data['label'] ); ?>
							<?php if ( ! in_array( 'integrated_payment_processing', (array) $method_data['supports'] ) ): ?>
								<span class="slicewp-payout-method-badge"><?php echo esc_html__( 'Manual', 'slicewp' ); ?></span>
							<?php endif; ?>
						</label>

					</div>

					<div class="slicewp-expandable-item-actions">
						<span class="slicewp-promo-pill slicewp-small"><?php echo esc_html__( 'Pro Feature', 'slicewp' ); ?></span>
					</div>

				</div>

			</div>

		<?php

	}

}
add_action( 'slicewp_view_settings_tab_payouts_payout_methods_bottom', 'slicewp_view_settings_tab_payouts_payout_methods_upsell' );