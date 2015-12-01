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
define('DB_NAME', 'wp_hoatet');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', '14121991');

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
define('AUTH_KEY',         'QNux#inLNc+yeJ1aq;/tUOF&rs(.$cE#yb@u<@qRtr.0qQE-US#FsR=ulP7HQW`^');
define('SECURE_AUTH_KEY',  '?B]] 1wx4gyy|J|&)|OZW5OTm34[$>ua@5-X~V_NqL+g+twfO+t@vDD_1;i7/l[L');
define('LOGGED_IN_KEY',    '($T2l5o&~TWC[k6cij!2bw%hbrFL]X[zNp!=+wfPmtsk=2-xkNDIV/gmhv-DB*( ');
define('NONCE_KEY',        '>yO7N8[i[Ag<epg:.!/ -&zeqnAq9KL#uNt32!-@D_@]@bzG@zO;:w%vI>ctmCmQ');
define('AUTH_SALT',        '^<vu#&tF-(9%em_rVJdy.D4O@QaJ|La=`TU3n||HsGeefu(Me#nu>}Jl4Pn&%mL-');
define('SECURE_AUTH_SALT', ',mtV@N~nID{n|f`C(eq0up>>M X]JXBsjT$YSgOab,sgx41V}L^w|[|y=OxHk~KA');
define('LOGGED_IN_SALT',   '6T&u{D2Pn4lne75&EH0s-]vev|:*-uPd;@(]BEazD?6GvR-Oq)2u~2oB oeLS?8U');
define('NONCE_SALT',       'A#<0E^OgKNg-?%dRLhYX(d2g2,@v&!-f xdy]9p>6o-7F8g)d`*!mE^,#N$j>G@T');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
