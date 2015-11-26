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
define('DB_NAME', 'cungung_hoatet');

/** MySQL database username */
define('DB_USER', 'cungung_admin');

/** MySQL database password */
define('DB_PASSWORD', 'khanh1hcmc!');

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
define('AUTH_KEY',         'MD9)~r_QMM=`umqE[zgm=]GBw&#^Ur]a5WciPO,&t>QBL?|I+? NB+8cq%y5wF4g');
define('SECURE_AUTH_KEY',  '-aSH/FdX=@{Jn*._D6|.I@s%Ogcf@r<YcA6,Zs4A9@A-!T?!j-3e&qX<!8vrP`TN');
define('LOGGED_IN_KEY',    '=FjcA( #MZh=H>5=GfeIMh}:bJxn[hU!y= #yr)[$LlI^9{ 6)rbX0k~/c%jEJ/+');
define('NONCE_KEY',        '|>}&ol/u-1@|3x<dJAzO0>P:e&_(K`,Hl6%.R-K+GMw d6N|5P R&{>js!Z0Omty');
define('AUTH_SALT',        'IK]~J}Y6nS(CV43RV1@s:6$UAhw+=Z][<p.RRH~_{)#`Ekbn.5&G<+~|u|ZRbn54');
define('SECURE_AUTH_SALT', '1WMAXN-[<bFnT0BTC~8-_*/K+9maV* 1LOA+mqyo!VIWQ(0zpJ JX=tO|L!H,_}[');
define('LOGGED_IN_SALT',   'pWW+|39,VljFSlv]=F:c]h*u7k&0L3$F~E=rE{NeRC9}GMQ@+YVp/pFl-!-Rr2MK');
define('NONCE_SALT',       'I~{V:R8&VWO*]}cuB;WYD5jxHdC.|H9ERw/~JiMblpfSv8C{7^boQ`/|WWG)Rz3-');

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
