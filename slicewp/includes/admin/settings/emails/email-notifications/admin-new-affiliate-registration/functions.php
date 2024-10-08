<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register the email notification sent to administrators when a new affiliate registers.
 *
 * @param array $email_notifications
 *
 * @return array
 *
 */
function slicewp_email_notification_admin_new_affiliate_registration( $email_notifications = array() ) {

	// Prepare notification data.
	$notification = array(
		'name' 		  => __( 'New Affiliate Registration', 'slicewp' ),
		'description' => __( 'The administrator will receive an email when a new affiliate registers an account.', 'slicewp' ),
		'recipient'   => 'admin',
		'merge_tags'  => array()
	);

	// Add merge tags.
	$merge_tags = new SliceWP_Merge_Tags();

	foreach ( $merge_tags->get_tags() as $tag_slug => $tag_data ) {

		if ( empty( $tag_data['category'] ) || in_array( $tag_data['category'], array( 'affiliate', 'general' ) ) ) {
			$notification['merge_tags'][] = $tag_slug;
		}

	}

	// Register notification.
	$email_notifications['admin_new_affiliate_registration'] = $notification;

	return $email_notifications;

}
add_filter( 'slicewp_available_email_notification', 'slicewp_email_notification_admin_new_affiliate_registration', 10 );


/**
 * Send an email notification to the admininstrators when a new affiliate registers.
 *
 * @param int $affiliate_id
 *
 */
function slicewp_send_email_notification_admin_new_affiliate_registration( $affiliate_id = 0 ) {

	// Verify if the notification request comes from backend.
	if ( is_admin() ) {
		return;
	}

	// Verify received arguments not to be empty.
	if ( empty( $affiliate_id ) ) {
		return;
	}

	// Verify if email notification sending is enabled.
	$notification_settings = slicewp_get_email_notification_settings( 'admin_new_affiliate_registration' );

    // Verify if email notification sending is enabled.
	if ( empty( $notification_settings['enabled'] ) ) {
		return;
	}
	
    // Verify if the email notification subject and content are filled in.
	if ( empty( $notification_settings['subject'] ) || empty( $notification_settings['content'] ) ) {
		return;
	}

	// Get the admin email addresses.
	$admin_emails = slicewp_get_setting( 'admin_emails' );

	if ( empty( $admin_emails ) ) {
		return;
	}

	// Put the admin emails in an array.
	$admin_emails = array_filter( array_map( 'trim', explode( ',', $admin_emails ) ) );

	// Remove items that are not email addresses.
	if ( ! empty( $admin_emails ) ) {

		foreach ( $admin_emails as $key => $value ) {
			if ( ! is_email( $value ) ) {
				unset( $admin_emails[$key] );
			}
		}

		$admin_emails = array_values( $admin_emails );

	}

	if ( empty( $admin_emails ) ) {
		return;
	}

	// Prepare the email subject and content.
	$email_subject = ( ! empty( $notification_settings['subject'] ) ? sanitize_text_field( $notification_settings['subject'] ) : '' );
	$email_content = ( ! empty( $notification_settings['content'] ) ? $notification_settings['content'] : '' );

	// Get the Affiliate object.
	$affiliate = slicewp_get_affiliate( $affiliate_id );

	// Replace the tags with data.
	$merge_tags = new SliceWP_Merge_Tags();
	$merge_tags->set_data( 'affiliate', $affiliate );

	$email_subject = $merge_tags->replace_tags( $email_subject );
	$email_content = $merge_tags->replace_tags( $email_content );

	// Send the email.
	slicewp_wp_email( $admin_emails, $email_subject, $email_content );

}
add_action( 'slicewp_register_affiliate', 'slicewp_send_email_notification_admin_new_affiliate_registration' );