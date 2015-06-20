<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Configuration for: Project URL
 * Put your URL here, for local development "127.0.0.1" or "localhost" (plus sub-folder) is fine
 */
define('URL', 'http://localhost/tuoitrebachkhoa_vp/');

/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'tuoitreb_vp');
define('DB_USER', 'root');
define('DB_PASS', '');
/**
 * Configuration for: Mailer (SMTP)
 *
 */
define('MAILER_HOST', 'smtp.gmail.com');
define('MAILER_PORT', '465');
define('MAILER_USER', 'duongtruc.92@gmail.com');
define('MAILER_PASS', '');
define('MAILER_FROM', 'info@tuoitrebachkhoa.edu.vn');
define('MAILER_FROM_NAME', 'Cổng thông tin tình nguyện TP.HCM');

/*define('MAILER_HOST', 'serv11.hostvn.net');
define('MAILER_PORT', '465');
define('MAILER_USER', 'info@tuoitrebachkhoa.edu.vn');
define('MAILER_PASS', '123456');
define('MAILER_FROM', 'info@tuoitrebachkhoa.edu.vn');
define('MAILER_FROM_NAME', 'Cổng thông tin tình nguyện TP.HCM');*/

/**
 * Configuration for: Views
 *
 * PATH_VIEWS is the path where your view files are. Don't forget the trailing slash!
 * PATH_VIEW_FILE_TYPE is the ending of your view files, like .php, .twig or similar.
 */
define('PATH_VIEWS', 'application/views/');
define('PATH_VIEW_FILE_TYPE', '.twig');
