<?php
if ( basename( $_SERVER['PHP_SELF'] ) == 'config.php' )
    die( 'This page cannot be loaded directly' );

define('SITE_URL', 'http://192.168.33.10/');
define('SITE_PATH', '/opt/little-software');
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'little');
define('MYSQL_PASS', 'software');
define('MYSQL_DB', 'littlesoftware');
define('MYSQL_PREFIX', 'little_');
// Only enable for developing!
define('SITE_DEBUG', true);
// Set to false to disable cross site request forgery protection (not recommended)
define('SITE_CSRF', true);
