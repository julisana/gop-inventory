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

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Pages</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../../styles/app.css" media="all" />

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
                integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
                integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
                crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header mb-4">
                    <div class="col-md-4">
                        <img src="../../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Inventory Valuation Report (<?php echo $year; ?>)</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav d-print-none">
                        <a href="index.php" class="btn btn-success">Admin Home</a><br />
                        <a href="index.php" class="btn btn-primary">Reports Home</a><br />
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-4 col-md-4 text-center">
                        Valuation Update Complete<br />
                        <a href="../index.php" class="btn btn-success">Admin Home</a>&nbsp;
                        <a href="index.php" class="btn btn-primary">Back to Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
