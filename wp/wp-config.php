<?php

error_reporting(0);

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'cowleyco');

/** MySQL database username */
define('DB_USER', 'gcowleyco');

/** MySQL database password */
define('DB_PASSWORD', 'weasel12');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         ';]WP{-twsf QcBB]Z!d4[`@=VVg(bxH.00WD<>(_8T*54:v4:b3oeiUl}R+u9X}|');
define('SECURE_AUTH_KEY',  'K|f4lbIS=3?=}u,}%8n,g<0`]/y<[jsTpiGPsQS<a]F[$|~}x1tDLhk[rsiT)v^E');
define('LOGGED_IN_KEY',    'g[-RpY:=Qet+!XTHK{ULZB+6RRmr7xz6o#.P AA3vgU[|j>+zHAkPLw!rAxjWs6@');
define('NONCE_KEY',        '9DuB_ck32P;._|$5_<|ZG-mXnj{W~w.[z;Y9Qhw}STegH}gym)5uY E#hnS?u|^9');
define('AUTH_SALT',        ']?WV,SnrB2?YJ@)+om&Qi*H>JQ*]Uoq1=yKM|2qM<^Q/>Lz7(fFK^F:Hvs s{nD`');
define('SECURE_AUTH_SALT', '_XL^Ji5<%I|EM-BM4|-kK][Hg>QxY)$a/SCUKg4r6n:8+3FyZ{9RQ?5__R8K!.|!');
define('LOGGED_IN_SALT',   'er?0v8VwaHOw~j+/;ZJL8#@:CE?!3=ewsBV@F+Vto%p~kIhxJ*w~_nJV 8c 7zX_');
define('NONCE_SALT',       'kFARr>sc&)+bpUzh+`|E*>#,kQV3,4l0-6{G<-sjrh&8|6T`0}#85+Ak%r[e2+`K');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
