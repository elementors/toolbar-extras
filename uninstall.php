<?php

// uninstall

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * If uninstall not called from WordPress, exit.
 *
 * @since 1.0.0
 *
 * @uses  WP_UNINSTALL_PLUGIN
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Various user checks.
 *
 * @since 1.0.0
 *
 * @uses  is_user_logged_in()
 * @uses  current_user_can()
 * @uses  wp_die()
 */
if ( ! is_user_logged_in() ) {

	wp_die(
		__( 'You must be logged in to run this script.', 'toolbar-extras' ),
		__( 'Toolbar Extras', 'toolbar-extras' ),
		array( 'back_link' => TRUE )
	);

}  // end if

if ( ! current_user_can( 'install_plugins' ) ) {

	wp_die(
		__( 'You do not have permission to run this script.', 'toolbar-extras' ),
		__( 'Toolbar Extras', 'toolbar-extras' ),
		array( 'back_link' => TRUE )
	);

}  // end if


/**
 * Delete all options and transients from the 'options' table in DB.
 *
 * @since 1.0.0
 *
 * @uses  delete_option()
 * @uses  delete_site_transient()
 */
function ddw_tbex_delete_options_transients() {

	/** Delete all options */
	delete_option( 'tbex-options-general' );
	delete_option( 'tbex-options-tweaks' );
	delete_option( 'tbex-options-development' );
	delete_option( 'tbex-plugin-version' );

	/** Delete all transients */
	delete_site_transient( 'tbex-notice-plugins-welcome' );
	delete_site_transient( 'tbex-notice-welcome' );

}  // end function


/**
 * Delete our options array (settings field) from the database.
 *    Note: Respects Multisite setups and single installs.
 *
 * @since  1.0.0
 *
 * @uses   ddw_tbex_delete_options_transients()
 *
 * @param  array $blogs
 * @param  int 	 $blog
 *
 * @global $wpdb
 */
/** First, check for Multisite, if yes, delete options on a per site basis */
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	
	global $wpdb;

	if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
		
		$sites = get_sites();
		
		foreach ( $sites as $site ) {
			
			switch_to_blog( $site->blog_id );

			/** Delete our stuff for Multisite sub-sites */
			ddw_tbex_delete_options_transients();

			restore_current_blog();
			
		}  // end foreach
		
	} else {

		$sites = wp_get_sites( array( 'limit' => 0 ) );
		
		foreach ( $sites as $site ) {
			
			switch_to_blog( $site[ 'blog_id' ] );

			/** Delete our stuff for Multisite sub-sites */
			ddw_tbex_delete_options_transients();

			restore_current_blog();
			
		}  // end foreach
		
	}  // end if

} else {	/** Otherwise, delete options from main options table */

	/** Delete our stuff */
	ddw_tbex_delete_options_transients();

}  // end if
