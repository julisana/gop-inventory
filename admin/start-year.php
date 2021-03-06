<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-17
 * Time: 21:00
 */

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\Keyer;
use GOP\Inventory\Models\CostCode;
use GOP\Inventory\Models\Manufacturer;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $year = date( 'Y' );
    if ( !empty( $_REQUEST[ 'year' ] ) ) {
        $year = $_REQUEST[ 'year' ];
    }
    $years = array_reverse( get_existing_years( $db ) );
    $previousYear = array_shift( $years );

    if ( in_array( $year, $years ) ) {
        //return and do the error stuff
    }

    $keyer = new Keyer();
    $keyer->setDB( $db );
    $manufacturer = new Manufacturer();
    $manufacturer->setDB( $db );
    $costCode = new CostCode();
    $costCode->setDb( $db );

    $keyers = $db->table( 'keyer' )
        ->fields( [ 'code', 'name' ] )
        ->where( [ 'year' => $previousYear ] )
        ->select();

    $manufacturers = $db->table( 'manufacturer' )
        ->fields( [ 'code', 'name' ] )
        ->where( [ 'year' => $previousYear ] )
        ->select();

    $costCodes = $db->table( 'cost_code' )
        ->fields( [ 'code', 'name', 'percentage', 'is_decrease', 'field' ] )
        ->where( [ 'year' => $previousYear ] )
        ->select();

    //Create items from the previous years
    foreach ( $keyers as $keyerItem ) {
        $keyerItem[ 'year' ] = date( 'Y' );
        $keyer->create( $keyerItem );
    }

    foreach ( $manufacturers as $manufacturerItem ) {
        $manufacturerItem[ 'year' ] = date( 'Y' );
        $manufacturer->create( $manufacturerItem );
    }

    foreach ( $costCodes as $codeItem ) {
        $codeItem[ 'year' ] = date( 'Y' );
        $costCode->create( $codeItem );
    }
}

redirect( 'index.php' );
