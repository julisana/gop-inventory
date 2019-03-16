<?php
if ( !isset( $row ) ) {
    $row = [
        'line_number' => '',
        'is_new' => '',
        'manufacturer' => '',
        'product_id' => '',
        'product_description' => '',
        'quantity' => '',
        'list_price' => '',
        'sell_price' => '',
        'cost' => '0.00', //For some reason checking to see if this field exists doesn't work if there isn't a value in it.
        'cost_code' => '',
        'value' => '',
    ];
}

if ( !isset( $index ) ) {
    $index = 0;
}

include '../inventory-row.php';
