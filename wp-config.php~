<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'superba_wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'tuxmealux');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '7&6L{u$b5?4Ii#|LM?sekmfc0v|RsY|5:4.>g:qjX&3K<1I,]<DKH<P|j%sRg[sD');
define('SECURE_AUTH_KEY',  '.+?K=*_i]S~BR[uP=I#X-I:J6f/:X7mjk`#L<a5.1pRK(6cXG>@Wp<&u-EyhVyf0');
define('LOGGED_IN_KEY',    '/VJD;=3h&jQC3/aqm-yEXB;,MAW0hF(# hOW34H#0u<JU7/wx_&i#)Z8^;0Ub_o}');
define('NONCE_KEY',        '{(/^*HY)Oz&y6$lZ=g/I(X+rLP[O;|MN~D:IEJ:_G5|ceZr<C6Z70+*>Kx3!}k<C');
define('AUTH_SALT',        '$MuQgzIcNuI&so1`~H0?GXbdE,KV.SXi?2z,h+{MnfZD-_s^p%8VgfH5|R2z+Pl+');
define('SECURE_AUTH_SALT', ';zp3G/#l AkQ?B>o`.?,+OKCq+TG)B@AY.poka@gh.Ht<b@gOg]>DIo$HXHEA{Ln');
define('LOGGED_IN_SALT',   '>)V!3f-3_<udz#r4$/qEYOU$UPHDT3U7YGB@VIx,tI|N5%w6QC#c|jxt>7kj-jy2');
define('NONCE_SALT',       ',k{|Lx9;_4|:h_tsv+MH9#F$S08Z0uY(TNOR)h%sj6!Z }URjqU3mC2|*|%L.(wl');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

