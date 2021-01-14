<?php


/** Enable W3 Total Cache */

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
define('DB_NAME', "ellulschranz");

/** MySQL database username */
define('DB_USER', "homestead");

/** MySQL database password */
define('DB_PASSWORD', "secret");

/** MySQL hostname */
define('DB_HOST', "localhost");

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
define('AUTH_KEY',         'bbdsgxn0txkki27rjtq01mn11rtxejf8otzm4svctfyzol4mhfgypr1mwwlcfwmw');
define('SECURE_AUTH_KEY',  'jkartnuhhmvpdtdcxh1qjekhhflwqa6vm6fowrtywsvmxu634bvfikx9ut9txjas');
define('LOGGED_IN_KEY',    'mfhpxhe8onf38szlkbdoodbdfnfmbtrt4nuxxow7spkfzsqbvgtmz2juuinbo7oc');
define('NONCE_KEY',        'yhcm5im0ckbgor8e5pfojb7zbrszke2ueq9cxkss8wpawzdndv1p87pkkm2rswzr');
define('AUTH_SALT',        '0fsncbg0vd5uti03lrsl5bpg0vzmc1j85g8kjy40haeswsjtimc5hsqpnazeddau');
define('SECURE_AUTH_SALT', 'vtafnis3iega96y0or35phlh0d72sz6ganruprvmeanangwm6whe4ej8vfq0pvgk');
define('LOGGED_IN_SALT',   'gzj6pfv6fjpzaxst74fs2pw6wkbio6sdwtpvu1h4u3nknxfnevraubgh9ax1zr2b');
define('NONCE_SALT',       'bu1i0efbksqszssstpr5ucew5tqbd0qf7vh1ph0c5gcwloya36uz5y4rduoucka2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpud_';

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
define( 'WP_MEMORY_LIMIT', '128M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );



@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system

