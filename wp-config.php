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
define('DB_NAME', 'portfolio');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'r8asOq`&hoI27ee:0N;]kl@L:Klbc+l:tnm]=Kfz7o}MFuTv.O] =x!7QG^Whorw');
define('SECURE_AUTH_KEY',  'wTiCA<#T[?UETC5,NiJ9iUYBp2B8%:qyo;XsuBbk3A;0_T:#Fa59_`VX9B`{a-kM');
define('LOGGED_IN_KEY',    '_C-t9rS()~,0%kd$:b`]t^R%!V+4KS=)S+fIbtjC*7(cPq/6Dxit%92PhjZlM^Lw');
define('NONCE_KEY',        'y2G.gmB$=SmE_0uU:Gu5xg+l+o5%8@J.9z_e1R9A+.GX%R1&[Y*eg&)fE 1G,p#i');
define('AUTH_SALT',        '>Z1_l {h<9l@VCik_k ? LjTft4v6nK`U 3h*Y;GST[Z;wa~a4?<]:.PhVme<*tf');
define('SECURE_AUTH_SALT', '52WC44E,|`R<9}qUy9FyiTx 65TVVy($hx/!-<Xp*`Qo9.$kk, RqXl[GrU(&ko7');
define('LOGGED_IN_SALT',   'S{Cx^3Y4^pPY  _kXuBCoTLbA$}4t/t%XT<S&C?D07B!C/tl7fd6|$Y9q+_)aPm*');
define('NONCE_SALT',       'Tg:68yCSL=_q3Y>?niLzx4+_dK?m,/t$oBF 9++DdtlsK!7C!;zf* 6@)2Xq%gzb');

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
