<?php
/*
Plugin Name: root Cookie Path
Plugin URI: http://www.linickx.com/blog/archives/274/root-cookie-v12-wordpress-plugin/
Description: Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog)
Author: Nick [LINICKX] Bettison
Version: 1.2
Author URI: http://www.linickx.com
License: Free to use non-commercially.
Warranties: None.

ChangeLOG:
V 1.0 : Original Release.
V 1.1 : Added the safety hook 'if(!function_exists('wp_setcookie')' since WP2.1 crashed out.
V 1.2 : Added logout function, as default on didn't work, thanks Aja! ( http://www.ajalapus.com/  )
*/

if(!function_exists('wp_setcookie')) {

function wp_setcookie($username, $password, $already_md5 = false, $home = '', $siteurl = '', $remember = false) {
	if ( !$already_md5 )
		$password = md5( md5($password) ); // Double hash the password in the cookie.

	if ( empty($home) )
		$cookiepath = '/'; // Was: $cookiepath = COOKIEPATH;
	else
		$cookiepath = '/'; // Was: $cookiepath = preg_replace('|https?://[^/]+|i', '', $home . '/' );

	if ( empty($siteurl) ) {
		$sitecookiepath = '/'; // Was: $sitecookiepath = SITECOOKIEPATH;
		#$cookiehash = COOKIEHASH; // We Don't need this :-)
	} else {
		$sitecookiepath = '/'; // Was: $sitecookiepath = preg_replace('|https?://[^/]+|i', '', $siteurl . '/' );
		#$cookiehash = md5($siteurl); // We Don't need this either :-)
	}

	if ( $remember )
		$expire = time() + 31536000;
	else
		$expire = 0;

	setcookie(USER_COOKIE, $username, $expire, $cookiepath, COOKIE_DOMAIN);
	setcookie(PASS_COOKIE, $password, $expire, $cookiepath, COOKIE_DOMAIN);

	if ( $cookiepath != $sitecookiepath ) {
		setcookie(USER_COOKIE, $username, $expire, $sitecookiepath, COOKIE_DOMAIN);
		setcookie(PASS_COOKIE, $password, $expire, $sitecookiepath, COOKIE_DOMAIN);
	}
}

}

if(!function_exists('wp_clearcookie')) {

        function wp_clearcookie() {
                setcookie(USER_COOKIE, ' ', time() - 31536000, '/',COOKIE_DOMAIN);
                setcookie(PASS_COOKIE, ' ', time() - 31536000, '/',COOKIE_DOMAIN);
        }
}

?>
