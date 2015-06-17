<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'pikawall');

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
define('AUTH_KEY',         'wTY=TA|C|Q6 }~6nS88]kYGk^|SCiGc|}Mkgux7k+nWzTG:-JJcJ(IV;(i-q|99|');
define('SECURE_AUTH_KEY',  '|w^&7!hhEY|jCeD]Jp*I>Fr> _,th9MEC++<>0;E]`R6=zoL-`+@W&MMd=%|.V)J');
define('LOGGED_IN_KEY',    '@[i=~*DYk{< Xx{gr||.z;EPpAn3)A9,-.x g9YgYxGwz*L,boBYU U?r0S&`a: ');
define('NONCE_KEY',        'Rh5>KIzdw<e?6D42vWNn|C:u06WZWMSoeM5N$hzMMxGO*f)w{L!+%-QMxY5-!LfU');
define('AUTH_SALT',        '_{=WovdW=Xa||!!{xgo!eV9^?lKJH;]Zsi_x9Xo5]b>{,<R%~ iMuWm(1p(~n2-{');
define('SECURE_AUTH_SALT', 'R<}h0h~n$f3.wakeX-TpliC1j~%aL6pl18P fc,J>|{; BfOU&eg=:#XJ0Y?]^LD');
define('LOGGED_IN_SALT',   '`$^6uT&eMe327F5r|{?ED/]<f( hfE!Dw8H2.R@&?spW+,UT|hc@#lLUBXhaNV* ');
define('NONCE_SALT',       'NIEtzSvXHG j%q{Z3tUTBA&IG(y~=Ij_>8|r&VYIY?Q_SNMLV(K;}w:&*9JV(R$H');

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
