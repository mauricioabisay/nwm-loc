<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_orden');

/** MySQL database username */
define('DB_USER', 'granmaestre');

/** MySQL database password */
define('DB_PASSWORD', '0rd3n');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'k)I5bGf|YQCAtIzxhydS/+1x=(bqLnAFN4_3(GL)?iR}P~kF4KIBn/fLodB>za_>');
define('SECURE_AUTH_KEY',  '|^]=X,%KcxS%yfRn2BhxpX.bO6qRW=l_h..QOqE,;(%JDp`OC%xhXz=o;KcHyxK4');
define('LOGGED_IN_KEY',    'ktKooXK9[{fj0c?a6NUFQQ&,C*o>iMF-4VyTldl>[R}r#ElRmQ+m/HF/8N$/D+1-');
define('NONCE_KEY',        'SY+>F?z+D%h0S_=bD8D(;@=wIo+5#m)[svG~QPn}w)j*hfD|;Cqt6{&T;?2,;$fj');
define('AUTH_SALT',        'oH$.q5rmw9Qc<Jt1QTm+<zwP2i-b1EW5EdcI-Dj[PSBl7E{@Zp~|A7(=XE{9Qw~j');
define('SECURE_AUTH_SALT', '4B,%p_N7MZN!$tUp08x.cJ=[^?dmty8~}#b[:j~xDh07.^H6fM.nPk]E|L6W0K^#');
define('LOGGED_IN_SALT',   'b7tyG)v^*Au-g]<h+FE|Bs7,:K~s:,0336##2-t~x9^U;Xs#V[Y|<nv9R5n9;J!z');
define('NONCE_SALT',       '`c<0p30()*wC-;7><~]+kTm+>U-:nymvq&]0Ou0 }&6OqkY`MuTL>EX_4.5MS(N$');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
