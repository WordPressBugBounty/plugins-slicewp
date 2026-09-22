<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class that handles admin notice registration and output.
 *
 */
Class SliceWP_Admin_Notices {

	/**
	 * The current instance of the object.
	 *
	 * @access private
	 * @var    SliceWP_Admin_Notices
	 *
	 */
	private static $instance;

	/**
	 * List of notices that have been registered.
	 *
	 * @access protected
	 * @var    array
	 *
	 */
	protected $notices = array();

	/**
	 * List of notices that will be printed in the page.
	 *
	 * @access protected
	 * @var    array
	 *
	 */
	protected $display_notices = array();


	/**
	 * Constructor.
	 *
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'catch_url_admin_notice' ), 100 );
		add_action( 'admin_init', array( $this, 'catch_flash_notices' ), 100 );
		add_action( 'admin_notices', array( $this, 'print_notices' ) );

	}


	/**
	 * Returns an instance of the object.
	 *
	 * @return SliceWP_Admin_Notices
	 *
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof SliceWP_Admin_Notices ) ) {
			self::$instance = new SliceWP_Admin_Notices;
		}

		return self::$instance;

	}


	/**
	 * Adds a new admin notice to the $notices property.
	 *
	 * @param string $slug
	 * @param string $message
	 * @param string $class
	 *
	 */
	public function register_notice( $slug, $message, $class = 'updated' ) {

		$this->notices[$slug] = array(
			'message' => $message,
			'class'   => $class
		);

	}


	/**
	 * Prepares a registered admin notice for printing on admin_notices action.
	 *
	 * @param string $slug
	 *
	 */
	public function display_notice( $slug ) {

		$this->display_notices[] = $slug;

	}


	/**
	 * Callback function to print admin notices.
	 *
	 */
	public function print_notices() {

		if ( ! isset( $this->display_notices ) ) {
			return;
		}
        
        $allowed_html = slicewp_get_kses_allowed_html();

        foreach ( $this->display_notices as $notice_slug ) {

        	if ( empty( $this->notices[$notice_slug] ) ) {
				continue;
			}

            echo '<div class="' . esc_attr( $this->notices[$notice_slug]['class'] ) . ' notice slicewp-notice slicewp-notice-' . esc_attr( str_replace( '_', '-', $notice_slug ) ) . '">';
                echo wp_kses( $this->notices[$notice_slug]['message'], $allowed_html );
            echo '</div>';

        }

	}


	/**
     * Catches messages sent through the URL.
     *
     */
    public function catch_url_admin_notice() {

        if ( empty( $_GET['slicewp_message'] ) ) {
			return;
		}

        $message_slug = sanitize_text_field( $_GET['slicewp_message'] );

        if ( ! empty( $this->notices[$message_slug] ) ) {
			$this->display_notice( $message_slug );
		}

    }


	/**
	 * Flashes an admin notice to the current user, to be shown on their next page load.
	 *
	 * Persisted in user meta (a single reusable row, blanked once shown) so the notice survives a
	 * redirect — letting an action strip its query arguments from the URL and still surface a
	 * message — without the wp_options AUTO_INCREMENT churn of a transient. Carries a dynamic
	 * message, unlike the slug-based URL notice caught by catch_url_admin_notice(). Multiple
	 * flashes stack, keyed by slug.
	 *
	 * @param string $slug
	 * @param string $message
	 * @param string $class    Notice class, e.g. 'error', 'updated', 'notice-warning'.
	 *
	 */
	public function flash_notice( $slug, $message, $class = 'updated' ) {

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$stored  = get_user_meta( $user_id, 'slicewp_flash_notices', true );
		$notices = ( is_array( $stored ) && ! empty( $stored['notices'] ) && is_array( $stored['notices'] ) ) ? $stored['notices'] : array();

		$notices[ $slug ] = array( 'message' => $message, 'class' => $class );

		update_user_meta( $user_id, 'slicewp_flash_notices', array( 'notices' => $notices, 'time' => time() ) );

	}


	/**
	 * Registers and displays any notices flashed to the current user, then clears them.
	 *
	 * The meta row is blanked (not deleted) once consumed so its umeta_id is never churned. A
	 * stale set — flashed but never shown, e.g. the tab was closed after the redirect — is
	 * discarded silently.
	 *
	 */
	public function catch_flash_notices() {

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$stored = get_user_meta( $user_id, 'slicewp_flash_notices', true );

		if ( ! is_array( $stored ) || empty( $stored['notices'] ) || ! is_array( $stored['notices'] ) ) {
			return;
		}

		// Blank instead of delete to avoid churning the meta row's umeta_id.
		update_user_meta( $user_id, 'slicewp_flash_notices', '' );

		// Discard a stale set that was never shown.
		if ( ! empty( $stored['time'] ) && ( time() - (int) $stored['time'] ) > MINUTE_IN_SECONDS ) {
			return;
		}

		foreach ( $stored['notices'] as $slug => $notice ) {

			if ( empty( $notice['message'] ) ) {
				continue;
			}

			$this->register_notice( $slug, $notice['message'], ! empty( $notice['class'] ) ? $notice['class'] : 'updated' );
			$this->display_notice( $slug );

		}

	}


}


/**
 * Returns the instance of SliceWP_Admin_Notices
 *
 * @return SliceWP_Admin_Notices
 *
 */
function slicewp_admin_notices() {

	return SliceWP_Admin_Notices::instance();

}

slicewp_admin_notices();