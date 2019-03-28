<?php
require_once( './../../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\InventoryItem;

$db = new DB();

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$codes = [];
$results = $db->table( 'cost_code' )
    ->where( [ 'year' => $year ] )
    ->select();

foreach ( $results as $result ) {
    $codes[ $result[ 'id' ] ] = $result;
}

$inventoryItems = $db->table( 'inventory' )
    ->where( [ 'year' => $year ] )
    ->orderBy( 'page ASC, line_number ASC' )
    ->select();

foreach ( $inventoryItems as $id => $row ) {
    $inventory = new InventoryItem();

    if ( empty( $row[ 'cost_code' ] ) ) {
        continue;
    }

    $value = 0.00;
    if ( isset( $codes[ $row[ 'cost_code' ] ] ) ) {
        $isDecrease = false;
        $code = $codes[ $row[ 'cost_code' ] ];
        $percentage = $code[ 'percentage' ];
        $field = $code[ 'field' ];

        if ( $percentage < 0 ) {
            $isDecrease = true;
            $percentage = abs( $percentage );
        }

        $difference = $row[ $field ] * $percentage;
        $value = $row[ $field ] + $difference;
        if ( $isDecrease ) {
            $value = $row[ $field ] - $difference;
        }
    }

    $row[ 'value' ] = number_format( $value, 2 ) * $row[ 'quantity' ];

    $inventory->setDB( $db )->save( $row );
}

redirect( 'index.php' );
