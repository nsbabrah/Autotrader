<?php
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
define( 'DB_NAME', 'autotrader' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'FDQ Y=P,2&yXh]CIjMPBB%pBv,J)@raWJcVa_hNB8a$tlXBNX,WmHWb,~Ep`TkD@' );
define( 'SECURE_AUTH_KEY',  '9ij2)i(>FBOAYCiUv*q ZX+Uq,qpDEgevYI[udwRq=[F(_Ejh-3x1W?o3J812f^(' );
define( 'LOGGED_IN_KEY',    'K*IePN r80@YA@L4&zeS6rfHp{Jl/!3AYMbO]$yBhSCzFM%)uigusw_&soULX_/&' );
define( 'NONCE_KEY',        '|tNh(B+]1U;wbh2eu^Di7<w)nU;`/#<{,V#!6P1c|knyOIK8hNz|8 m^DI.MGaw[' );
define( 'AUTH_SALT',        ')s|2 MS+JB^H&QLNHA*o/6!It9.^uiUz> w.}5|ia52;lQSw}ig_WGR_4q`[FXlI' );
define( 'SECURE_AUTH_SALT', 'dNG<5ryn$qzb;ouFs9rO; pAN7dl^]Q[}W>xST[8=-csX`EfbK ;lg~:+4(,U:YB' );
define( 'LOGGED_IN_SALT',   ':cS<O|z3:)6,pHcsT7u+k}|c;92XGz$r]oDWk-$++=u@XL9b,O~P+>|YzbN-Q&8#' );
define( 'NONCE_SALT',       '}x* EHJn1UL6#][cgC!9)-%1X!KgLa>[AfO,|_3nG|kk `0fgL|2=3(c=ue HXEX' );

/**#@-*/

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */


define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */
define( 'FS_METHOD', 'direct' );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
