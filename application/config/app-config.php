<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (file_exists(__DIR__ . '/app-config.local.php')) {
    require_once __DIR__ . '/app-config.local.php';
}
/*
* --------------------------------------------------------------------------
* Base Site URL
* --------------------------------------------------------------------------
*
* URL to your CodeIgniter root. Typically this will be your base URL,
* WITH a trailing slash:
*
*   http://example.com/
*
* If this is not set then CodeIgniter will try guess the protocol, domain
* and path to your installation. However, you should always configure this
* explicitly and never rely on auto-guessing, especially in production
* environments.
*
*/
defined('APP_BASE_URL') or define('APP_BASE_URL', 'https://portalcliente.app.br/admin');

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*
* If you use the Encryption class, you must set an encryption key.
* See the user guide for more info.
*
* http://codeigniter.com/user_guide/libraries/encryption.html
*
* Auto added on install
*/
defined('APP_ENC_KEY') or define('APP_ENC_KEY', '450ba03ef17ce23da918045744422e72');

/**
 * Database Credentials
 * The hostname of your database server
 */
//define('APP_DB_HOSTNAME', '162.214.205.254');
//define('APP_DB_HOSTNAME', 'localhost'); 162.214.98.202
defined('APP_DB_HOSTNAME') or define('APP_DB_HOSTNAME', '54.158.243.192');
/**
 * The username used to connect to the database
 */
defined('APP_DB_USERNAME') or define('APP_DB_USERNAME', 'wwsigp_sig');
//define('APP_DB_USERNAME', 'sigplusapp_admin');  VPS
//define('APP_DB_USERNAME', 'root');
/**
 * The password used to connect to the database
 */
defined('APP_DB_PASSWORD') or define('APP_DB_PASSWORD', 'Sigplus*2024');
//define('APP_DB_PASSWORD', 'Sigplus#2023');
//define('APP_DB_PASSWORD', 'W3b#Un1m3d');
/**
 * The name of the database you want to connect to
 */
 defined('APP_DB_NAME_DEFAULT') or define('APP_DB_NAME_DEFAULT', 'wwsigp_sigplus');
//define('APP_DB_NAME_DEFAULT', 'sigplusapp_sigplus');

//define('APP_DB_USERNAME', 'sigplus_admin');
/**
 * The password used to connect to the database
 */
//define('APP_DB_PASSWORD', 's!gplu$.21');
/**
 * The name of the database you want to connect to
 */
//define('APP_DB_NAME_DEFAULT', 'sigplus_vision');


/**
 * @since  2.3.0
 * Database charset
 */
define('APP_DB_CHARSET', 'utf8');
/**
 * @since  2.3.0
 * Database collation
 */
define('APP_DB_COLLATION', 'utf8_general_ci');

/**
 *
 * Session handler driver
 * By default the database driver will be used.
 *
 * For files session use this config:
 * define('SESS_DRIVER', 'files');
 * define('SESS_SAVE_PATH', NULL);
 * In case you are having problem with the SESS_SAVE_PATH consult with your hosting provider to set "session.save_path" value to php.ini
 *
 */
define('SESS_DRIVER', 'database');
define('SESS_SAVE_PATH', 'sessions');

/**
 * Enables CSRF Protection
 */
define('APP_CSRF_PROTECTION', true);