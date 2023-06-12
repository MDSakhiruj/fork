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
define( 'DB_NAME', 'fork_db' );

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
define( 'AUTH_KEY',         'gL_:_lfr5`w%6 N$(}?NH&(Rc:J7px rkanrffC2ZNedL7m+Yn/F4isL@a3>+G%W' );
define( 'SECURE_AUTH_KEY',  ' ?!+jQ}kVKzlFeG,td*>{UrxeM) f`kV$y-@,cO<`(;TNyj Dry=xyQRQQ~2>c]]' );
define( 'LOGGED_IN_KEY',    'na0 ;hsU]Qei`:_-{-F%gKi2a-to`^v``7|p]$,`ZQ{6e4BKP}ng&@tp_@B:%,b+' );
define( 'NONCE_KEY',        '$pPLPYwmzdS_o7}NV!Y$W4WyV/(,.a=yqJ/F[Ygp&Sx[{2tfO9x$~l<4iu>)lYvJ' );
define( 'AUTH_SALT',        '}ok$*SS2&Y6VP)M)@C9TV u?6B=uWu64;=GIV0pD?r[pzeunv(<[oY .]/aB( FR' );
define( 'SECURE_AUTH_SALT', '7IyOhJ`Sl9G4Tm0>0Cp5~x%U;y]h~VWvZ$/%x6C;G:1bv<(tj`Lzh/o5<q1jw|fi' );
define( 'LOGGED_IN_SALT',   '8d8lgdhglfTPT*}LA%)afB3{YnTzO0>HqVd9?_OQt:0*}@4BB_&/^cj?7yiW(->*' );
define( 'NONCE_SALT',       ':9$]$#apUn=XftrS~8{E)Bq6RWjjI1lYb$MCK*%mz3,pX$[A!w%P4Y-=^N@@edWr' );

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
