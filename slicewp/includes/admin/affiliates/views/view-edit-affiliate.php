<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Verify for Affiliate ID.
$affiliate_id = ( ! empty( $_GET['affiliate_id'] ) ? absint( $_GET['affiliate_id'] ) : 0 );

if ( empty( $affiliate_id ) ) {
	return;
}

// Get the Affiliate Data
$affiliate = slicewp_get_affiliate( $affiliate_id );

if ( is_null( $affiliate ) ) {
	return;
}

$affiliate_reject_reason = slicewp_get_affiliate_meta( $affiliate_id, 'reject_reason', true );

// Get the Affiliate's User
$user = get_user_by( 'id', $affiliate->get('user_id') );

if ( ! $user ) {
	return;
}

?>

<div class="wrap slicewp-wrap slicewp-wrap-edit-affiliate">

	<form action="" method="POST">

		<!-- Page Heading -->
		<h1 class="wp-heading-inline"><?php echo __( 'Edit Affiliate', 'slicewp' ); ?></h1>
		<hr class="wp-header-end" />

		<div id="slicewp-content-wrapper">

			<!-- Primary Content -->
			<div id="slicewp-primary">

				<!-- Postbox -->
				<div class="slicewp-card slicewp-first">

					<div class="slicewp-card-header">
						<span class="slicewp-card-title"><?php echo __( 'Affiliate Details', 'slicewp' ); ?></span>
					</div>

					<!-- Form Fields -->
					<div class="slicewp-card-inner">

						<!-- Affiliate Name -->
						<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-name"><?php echo __( 'Affiliate Name', 'slicewp' ); ?></label>
								<?php echo slicewp_output_tooltip( sprintf( __( 'This is the display name of the user attached to this affiliate. You can change this value from the %suser edit page%s.', 'slicewp' ), '<a href="' . esc_url( add_query_arg( array( 'user_id' => $affiliate->get('user_id') ), admin_url( 'user-edit.php' ) ) ) . '">', '</a>' ) ); ?>
							</div>

							<input id="slicewp-affiliate-name" name="affiliate_name" disabled type="text" value="<?php echo esc_attr( slicewp_get_affiliate_name( $affiliate ) ); ?>" />

						</div>

						<!-- Affiiate Email -->
						<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-email"><?php echo __( 'Email', 'slicewp' ); ?></label>
							</div>

							<input id="slicewp-affiliate-email" name="affiliate_email" disabled type="text" value="<?php echo esc_attr( $user->get('user_email') ); ?>" />

						</div>

						<!-- Affiliate ID -->
						<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-affiliate-id"><?php echo __( 'Affiliate ID', 'slicewp' ); ?></label>
							</div>

							<input id="slicewp-affiliate-affiliate-id" name="affiliate_id" disabled type="text" value="<?php echo esc_attr( $affiliate->get('id') ); ?>" />

						</div>

						<!-- User ID -->
						<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-user-id"><?php echo __( 'User ID', 'slicewp' ); ?></label>
							</div>

							<input id="slicewp-affiliate-user-id" name="user_id" disabled type="text" value="<?php echo esc_attr( $affiliate->get('user_id') ); ?>" />

						</div>

						<!-- Registration Date -->
						<div class="slicewp-field-wrapper slicewp-field-wrapper-inline">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-registration-date"><?php echo __( 'Registration Date', 'slicewp' ); ?></label>
							</div>

							<input id="slicewp-affiliate-registration-date" name="registration_date" disabled type="text" value="<?php echo slicewp_date_i18n( esc_attr( $affiliate->get('date_created') ) ); ?>" />

						</div>

						<?php

							/**
							 * Hooks to output form fields
							 *
							 * @param string $form
							 *
							 */
							do_action( 'slicewp_admin_form_fields', 'edit_affiliate' );

						?>

						<!-- Affiliate Status -->
						<div class="slicewp-field-wrapper slicewp-field-wrapper-inline <?php echo ( $affiliate->get('status') != 'rejected' ? 'slicewp-last' : '' )?>">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-status"><?php echo __( 'Status', 'slicewp' ); ?> *</label>
							</div>

							<select id="slicewp-affiliate-status" name="status" class="slicewp-select2">

								<?php
									foreach( slicewp_get_affiliate_available_statuses() as $status_slug => $status_name ) {
										echo '<option value="' . esc_attr( $status_slug ) . '" ' . selected( $affiliate->get('status'), $status_slug, false ) . '>' . $status_name . '</option>';
									}
								?>

							</select>

						</div>

						<?php if ( $affiliate->get('status') == 'rejected' ): ?>

							<!-- Reject Reason -->
							<div class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-last">

								<div class="slicewp-field-label-wrapper">
									<label for="slicewp-affiliate-reject-reason"><?php echo __( 'Reject Reason', 'slicewp' ); ?></label>
								</div>

								<textarea id="slicewp-affiliate-reject-reason" name="reject_reason" disabled><?php echo $affiliate_reject_reason; ?></textarea>

							</div>

						<?php endif; ?>

					</div>

				</div>

				<!-- Payout Details -->
				<?php

					$payout_methods        = slicewp_get_payout_methods();
					$default_payout_method = slicewp_get_default_payout_method();

					$selected_payout_method = slicewp_get_affiliate_meta( $affiliate_id, 'payout_method', true );
					$selected_payout_method = ( ! empty( $selected_payout_method ) && in_array( $selected_payout_method, array_keys( $payout_methods ) ) ? $selected_payout_method : '' );

					$default_method_label = ! empty( $default_payout_method ) && ! empty( $payout_methods[ $default_payout_method ] )
						? sprintf( __( 'Default payout method (%s)', 'slicewp' ), $payout_methods[ $default_payout_method ]['label'] )
						: __( 'Default payout method', 'slicewp' );

				?>

				<div class="slicewp-card">

					<div class="slicewp-card-header">
						<span class="slicewp-card-title"><?php echo __( 'Payout Details', 'slicewp' ); ?></span>
					</div>

					<div class="slicewp-card-inner">

						<div id="slicewp-field-wrapper-payout-method" class="slicewp-field-wrapper slicewp-field-wrapper-inline slicewp-tooltip-wide">

							<div class="slicewp-field-label-wrapper">
								<label for="slicewp-affiliate-payout-method"><?php echo __( 'Payout Method', 'slicewp' ); ?></label>
								<?php echo slicewp_output_tooltip(
									'<p>' . __( "The payout method used when processing payments for this affiliate. If not set, the site-wide Default Payout Method will be used.", 'slicewp' ) . '</p>'
									. ( ! empty( $default_payout_method ) && ! empty( $payout_methods[ $default_payout_method ] )
										? '<p>' . sprintf( __( 'Current default: %1$s. You can change it in %2$sSettings &rarr; Payouts%3$s.', 'slicewp' ), '<strong>' . esc_html( $payout_methods[ $default_payout_method ]['label'] ) . '</strong>', '<a href="' . esc_url( add_query_arg( array( 'page' => 'slicewp-settings', 'tab' => 'payouts' ), admin_url( 'admin.php' ) ) ) . '">', '</a>' ) . '</p>'
										: '<p>' . sprintf( __( 'You can configure the default in %sSettings &rarr; Payouts%s.', 'slicewp' ), '<a href="' . esc_url( add_query_arg( array( 'page' => 'slicewp-settings', 'tab' => 'payouts' ), admin_url( 'admin.php' ) ) ) . '">', '</a>' ) . '</p>'
									)
								); ?>
							</div>

							<div class="slicewp-field-locked-wrapper">
								<input type="text" disabled value="<?php echo empty( $selected_payout_method ) ? esc_attr( $default_method_label ) : esc_attr( $payout_methods[ $selected_payout_method ]['label'] ); ?>" />
								<a class="slicewp-field-unlock" href="#"><?php echo slicewp_get_svg( 'outline-pencil-square' ); ?><?php echo __( 'Change', 'slicewp' ); ?></a>
							</div>

							<select style="display: none;" id="slicewp-affiliate-payout-method" name="payout_method" class="slicewp-select2" data-default-method="<?php echo esc_attr( $default_payout_method ); ?>">
								<option value=""><?php echo esc_html( $default_method_label ); ?></option>

								<?php foreach ( $payout_methods as $slug => $details ): ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $selected_payout_method, $slug ); ?>><?php echo esc_html( $details['label'] ); ?></option>
								<?php endforeach; ?>
							</select>

						</div>

						<?php

							/**
							 * Hook for payout-method-specific fields inside the Payout Details card.
							 *
							 * Each registered payout method can hook in here to output its own setup
							 * fields (e.g. PayPal email, etc.).
							 * Fields should be wrapped in a <div data-payout-method="[method_slug]">
							 * element so the core JS can show/hide them based on the selected method.
							 *
							 * @param string $context      'edit_affiliate'
							 * @param int    $affiliate_id  The ID of the affiliate being edited.
							 * 
							 */
							do_action( 'slicewp_view_affiliates_payout_details_fields', 'edit_affiliate', $affiliate_id );

						?>

					</div>

				</div>

				<?php

					/**
					 * Hook to add extra cards if needed
					 *
					 */
					do_action( 'slicewp_view_affiliates_edit_affiliate_bottom' );

				?>

			</div><!-- / Primary Content -->

			<!-- Sidebar Content -->
			<div id="slicewp-secondary">

				<?php

					/**
					 * Hook to add extra cards if needed in the sidebar
					 *
					 */
					do_action( 'slicewp_view_affiliates_edit_affiliate_secondary' );

				?>

			</div><!-- / Sidebar Content -->

		</div>

		<!-- Hidden affiliate id field -->
		<input type="hidden" name="affiliate_id" value="<?php echo esc_attr( $affiliate_id ); ?>" />

		<!-- Action and nonce -->
		<input type="hidden" name="slicewp_action" value="update_affiliate" />
		<?php wp_nonce_field( 'slicewp_update_affiliate', 'slicewp_token', false ); ?>

		<!-- Submit -->
		<div id="slicewp-content-actions">

			<input type="submit" class="slicewp-form-submit slicewp-button-primary" value="<?php echo __( 'Update Affiliate', 'slicewp' ); ?>" />

			<span class="slicewp-trash"><a onclick="return confirm( '<?php echo __( "Are you sure you want to delete this affiliate?", "slicewp" ); ?>' )" href="<?php echo wp_nonce_url( add_query_arg( array( 'page' => 'slicewp-affiliates', 'slicewp_action' => 'delete_affiliate', 'affiliate_id' => absint( $affiliate->get( 'id' ) ) ) , admin_url( 'admin.php' ) ), 'slicewp_delete_affiliate', 'slicewp_token' ); ?>"><?php echo __( 'Delete affiliate', 'slicewp' ) ?></a></span>

		</div>

	</form>

</div>
