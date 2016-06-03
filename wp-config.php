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
define('DB_NAME', 'dev_publicus');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
 define('AUTH_KEY',         'rPhvFGt%|%_1b|QX1by)iC$axTS#j5|_Az?5M[T(R+;M+% O{)Wp|pmu_;-hMh8{');
 define('SECURE_AUTH_KEY',  '<SLiT-O5@0=J[5&wMkX+Y)|hhOK_|L+K$lT+_w:Ag>W;<Ff_zqy34*`:!^xM|?Dy');
 define('LOGGED_IN_KEY',    '2R2wdn{ =V;qW+Sz5]LT*L#-X]B6MSMF8AZ745]L>7k/} W5lXN@Q+C?w/fR$mH ');
 define('NONCE_KEY',        'hf+U2V@@4Ee(4 $T/_MQYkH-_|H%+@3CWg3P!N[-yU2>O~{-:%^8T?)%%vw5Md}R');
 define('AUTH_SALT',        'xtE<.YuK1y[my~}Y1};Yu5[7:BPERH|Q:Rq0u|W@eS3Kb/jj}C+lEcq0+N-mA`_c');
 define('SECURE_AUTH_SALT', 'loC-}m585dRZnER,+<IPZ$vlp|Y4R+UT~a+]ui)E;2m>*10/*Q9~li@@9}@kPQ/^');
 define('LOGGED_IN_SALT',   'z0=C3OTY-_-i~AK:p5CK@~Bl+.2 E+s<j_`w}_yyA@JuxE(ES}E!_g%w9I]Ft>Ty');
 define('NONCE_SALT',       'sD9E+=K<h9N<Pmt~Qgye9x{%&2kDx9:IgadS*eQJ0bhb|{Fvh0,x`Jl >tbnpYbs');

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
