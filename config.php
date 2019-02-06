<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-08
 * Time: 17:08
 */

$root = realpath( $_SERVER[ "DOCUMENT_ROOT" ] );

require_once( $root . '/inventory/vendor/autoload.php' );

define( 'DB_HOSTNAME', 'localhost' );
define( 'DB_USERNAME', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_DATABASE', 'gop_inventory' );
