# Brute Force Login Prevention

Brute Force Login Prevention isn't as much a security measure as it is a **stop this brute force bot from swamping my server, please** measure.

By preventing access to the default `wp-admin/` and `wp-login.php` URLs used by WordPress during the login process, you can quickly get a bot to go elsewhere to look for prey. Combine this with username other than `admin` and a secure password and things are looking pretty good.

## To Setup

### Nginx Setup

1. Include the contents of `brute-force-login-prevention.conf` in your site's Nginx configuration. This can be done with some careful copy/paste or with the Nginx [include directive](http://wiki.nginx.org/CoreModule#include).
1. Put `brute-force-login-prevention.php` in the `wp-content/mu-plugins/` directory of your WordPress installation.
1. Restart Nginx.

### Apache Setup
1. Include the contents of `htaccess-additions.txt` in your site's Apache configuration. This can be done with some careful copy/paste to the beginning of your .htaccess file in the root of your site.
1. Put `brute-force-login-prevention.php` in the `wp-content/mu-plugins/` directory of your WordPress installation.

## Note
1. Using this method disables the **Lost Your Password** functionality of WordPress.
