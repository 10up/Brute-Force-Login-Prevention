<?php
/**
 * Plugin Name: Brute Force Login Prevention
 * Description: Assists in preventing common brute force login attempts by modifying the default login URL for WordPress.
 * Version: 0.2
 * Author: jeremyfelt, 10up
 * Author URI: http://10up.com
 * License: GPL2
 *
 * Plugin to be dropped in the `wp-content/mu-plugins/` directory so that
 * it is activated by default. Requires that the corresponding rules be
 * available for handling the new `site-login` in Nginx (or Apache).
 *
 * You can change any instances of `site-login` below to match a custom login
 * prompt, but be sure to change the corresponding rules in Nginx as well.
 */

add_filter( 'site_url', 'tenup_modify_login_post', 10, 3 );
/**
 * Add a `loginorigin` parameter to the wp-login.php POST request that is
 * detected in Nginx or Apache to determine the validity of the login attempt.
 *
 * @param $url string URL used for wp-login.php POST request
 * @param $path string the current path
 * @param $scheme string
 *
 * @return string string URL used for wp-login.php POST request
 */
function tenup_modify_login_post( $url, $path, $scheme ) {
	if ( 'login_post' === $scheme && 'wp-login.php' === $path ) {
		return esc_url( $url . '?loginorigin=site-login' );
	}
	if ( 'login_post' === $scheme && 'wp-login.php?action=lostpassword' === $path ) {
		return esc_url( $url . '&loginorigin=site-login' );
	}

	return $url;
}

add_filter( 'login_redirect', 'tenup_modify_login_redirect', 10, 1 );
/**
 * Normally a successful login causes a redirect to `domain/wp-admin/`. As we
 * are blocking any requests that go directly to this URL, we need to make sure
 * that index.php is appended to the request, resulting in `domain/wp-admin/index.php`.
 *
 * @param $redirect_to string URL to redirect to after login
 *
 * @return string URL to redirect to after login
 */
function tenup_modify_login_redirect( $redirect_to ) {
	if ( $redirect_to === admin_url() )
		return esc_url( admin_url( '/index.php' ) );

	return $redirect_to;
}

add_filter( 'lostpassword_url', 'tenup_modify_lost_password_url', 10, 1 );
/**
 * Add a `loginorigin` parameter to the wp-login.php lost password url
 * that is detected to determine the validity of the lost password request
 *
 * @param $url string URL used for wp-login.php lost password screen load
 *
 * @return string URL used for wp-login.php lost password screen load
 */
function tenup_modify_lost_password_url( $url ) {
	return esc_url( $url . '&loginorigin=site-login' );
}

add_filter( 'lostpassword_redirect', 'tenup_modify_lost_password_redirect_to', 10, 1 );
/**
 * If the redirect_to hidden form field on the lost password form is left blank
 * populate it with the default WordPress url wp-login.php?checkemail=confirm
 * and append the `loginorigin` parameter that is detected to determine
 * the validity of the request
 * WordPress Default Behavior Note:
 * If the lostpassword_redirect hidden form field on the lost password recovery page
 * is left blank (which is the default), 
 * the default behavior is to redirect to wp-login.php?checkemail=confirm
 *
 * @param $url string URL used for value of
 * hidden input redirect_to in lost password form wp-login.php POST request
 *
 * @return string URL to redirect to after password recovery
 */
function tenup_modify_lost_password_redirect_to( $url ) {
	return ( trim($url)=='' ? esc_url( site_url( 'wp-login.php?checkemail=confirm&loginorigin=site-login' ) ) : $url );
}
