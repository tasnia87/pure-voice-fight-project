<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'Kyh4QFpLXzliKDs8KEx5b3UscENfPlJnViQraSp+a2tdJF1rSlJobSVDW3wseG1eZERiZ2RET2tnWVBkOENIMQ==' );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'purevoicefight' );

/** Database username */
define( 'DB_USER', 'wordpressuser' );

/** Database password */
define( 'DB_PASSWORD', 'Tasnia2408@' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('FS_METHOD', 'direct');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0ywCLPnp_t!]o4NCk(+(|)@-y/`kUr9lMPc{t%R{+laX9c+lvZtdckuX[+>suHny');
define('SECURE_AUTH_KEY',  'O/uBKcOF[kM+Zwz;%UsI@]t}Gzy!L]Ox+Tg@1n5H6GnuGD;|6`|4S {0QB7+3spv');
define('LOGGED_IN_KEY',    '/U+^qRV@(|p;1QW9!`}qtz0.z4-NpYoK72Sd|+<)S:ID.6pmMVnA`M3miIcmFoPw');
define('NONCE_KEY',        'L24WVM32vn nJ<SlWCu1taa&~ZR-1v@{(dkU#b#vG&r[79{Ef; xqPq!Js5so<<4');
define('AUTH_SALT',        'i.?[<m]Io{(w7V:(?O^6H=nZqd[`$_^JsmCD {5W|T5]Bg0jot]#h7lvlv<3-7ov');
define('SECURE_AUTH_SALT', 'r$t|Vml]za%)Yl)|kQuS#H~:no KVVMyvpUZh9#,D%GqQ*~ocmY?Wee(iK)}EZ>Q');
define('LOGGED_IN_SALT',   'T72O+jhP9JD@+`S{-QZeiS33%a<Ou{t>zHeT$q|-@h@BKDaF3He;;57:(UI>-LE8');
define('NONCE_SALT',       'NHKydX^3&_[pXuS;$4-X3{[cTPV%UA^ P,HNpdf5/x/MLeG*w[j79p37NJ%n>yO?');



/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
//define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Enables page caching for Cache Enabler. */
if ( ! defined( 'WP_CACHE' ) ) {
	define( 'WP_CACHE', true );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
