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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'abelohost' );

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
define( 'AUTH_KEY',         'V/@M3P{Lv)ya&~SKVL<,Nr[^uZG~-+.ash^K:jA1RD%G_]ye0pEuQrEYXl:APbG8' );
define( 'SECURE_AUTH_KEY',  '+R[JWbJI[7=t+Kt(0el$9$ypo{r:m>Y#E~~)n2y3HbC(>a;C9Yk2Zxf/GgP2]4U%' );
define( 'LOGGED_IN_KEY',    'IF~HMLUG>!!(|n/|):LwDG%w2-/avyK:jv7neo!|V}I~x!FT^=CQEfn6TCh%.W=Z' );
define( 'NONCE_KEY',        'o&hKT;(^VQEC$GqEeKbJCQ(8|!V4IVRX))vDmMnwt_X]3Nr!R;i33Y=ICi$1|ysz' );
define( 'AUTH_SALT',        '<|`+19dxt*($}TZ|Bdx<iIxgIJ=L)NC|G7(ZGTFK<b[,t?(_$RXp?``.|aOd5e5J' );
define( 'SECURE_AUTH_SALT', 'WoHEPOgY)<!R.@as4se(XqqpV#@hz0@.fH*f4)i Ub6yXZgwNRMs;s+#xRZ2&_cv' );
define( 'LOGGED_IN_SALT',   '<8N>E/bC5etIo}xgZ!bpfs0gI5G?;?EvW0/:qmM6srC=R 0BVryCuOKfcC9z| D&' );
define( 'NONCE_SALT',       '5!K:T98cq8Xc3E1CR]a=DUgeupdPPQ xE&8}#%}iA} |vs3@bcw(R|bpr8l9;E>f' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
