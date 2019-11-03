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
define( 'DB_NAME', 'sql_9team_codeda' );

/** MySQL database username */
define( 'DB_USER', 'sql_9team_codeda' );

/** MySQL database password */
define( 'DB_PASSWORD', 'bxnF8JKPRdTepxwk' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']RJV4?4@3MN=$A2h/ t_W^MvQZ{}-o*wsya<.2rLGD<O,Y8b)XZk2/cHr>lpFs ~' );
define( 'SECURE_AUTH_KEY',  '#H[cUchI$.T0g0,w}YRLz}E)&YjM,Ez_MhO<w 9+.[n~yg/;9HNt@.B)i_S)#7SJ' );
define( 'LOGGED_IN_KEY',    '0op/2<{C8Ew2.%*OY[Bfx.9TdFX%StNTH*F!bCoY?gr#af9HPC5<#`$IwSBRMY<Q' );
define( 'NONCE_KEY',        'L;1L7Bu`f#}xtixETS?V+a]afQgYIRs+rC8@udKy):OR[kpF5A^c9fKqZa!%9&S0' );
define( 'AUTH_SALT',        '~jr7FKa5#]v&C1.fb[l>eYF&S;Wr:I ,r7:G9dSh$|:+WwU5uHJcSaeeXMJ3 HR6' );
define( 'SECURE_AUTH_SALT', 'TO_kf/rnqV~n.5#9j>YbYg%zK$M&rGxG8XKFHj/. P37-~;{;,%,uV[@vg<EXkm^' );
define( 'LOGGED_IN_SALT',   ')!nU? {,zvX{j*B*E8`X+tFRl8XKu$9zKu,?U`Bu=LCR/.nrOs8 `{&f?R.Zn4[M' );
define( 'NONCE_SALT',       'vC;Pd*U@y<;_=CW=nATUg=huQ?fNu9Vd;0RBNLt_N?}:J MCDs0`j]+;;8hIK~Zy' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '9team_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
