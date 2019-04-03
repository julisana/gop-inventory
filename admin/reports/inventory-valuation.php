<?php
//page number
//manufacturer
//product number
//product description
//quantity
//list price
//list price * quantity
//sell price
//sell price * quantity
//cost
//cost * quantity
//value
//-----
//Grand Total
//SUM: list price * quantity
//SUM: sell price * quantity
//SUM: cost * quantity
//SUM: value

require_once( './../../config.php' );

use GOP\Inventory\DB;

$db = new DB();

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$inventory = $db->table( 'inventory' )
    ->where( [ 'year' => $year ] )
    ->orderBy( 'page ASC, line_number ASC' )
    ->select();

$manufacturers = [];
$rows = $db->table( 'manufacturer' )
    ->fields( [ 'id', 'code' ] )
    ->where( [ 'year' => $year ] )
    ->select();
foreach ( $rows as $row ) {
    $manufacturers[ $row[ 'id' ] ] = $row[ 'code' ];
}

$results = $db->table( 'inventory' )
    ->fields( [ 'distinct cost_code' ] )
    ->where( [ 'year' => $year ] )
    ->select();

$codes = [];
$rows = $db->table( 'cost_code' )
    ->fields( [ 'id', 'code', 'percentage', 'field' ] )
    ->where( [ 'year' => $year ] )
    ->select();
foreach ( $rows as $row ) {
    $codes[ $row[ 'id' ] ] = [ 'code' => $row[ 'code' ], 'percentage' => $row[ 'percentage' ], 'field' => $row[ 'field' ] ];
}

$costCodes = [];
foreach ( $results as $row ) {
    $costCodes[ $row[ 'cost_code' ] ] = [
        'count' => 0,
        'total_list_price' => 0,
        'total_sell_price' => 0,
        'total_cost' => 0,
        'total_value' => 0,
    ];
}

$grandTotal = 0;
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
                <?php
                $keys = array_keys( $costCodes );
                $first = reset( $keys );
                ?>
                <?php foreach ( $costCodes as $costCode => $values ) { ?>
                    <div class="row header mb-4 <?php echo ( $costCode == $first ) ? '' : 'd-none' ?>">
                        <div class="col-md-4">
                            <img src="../../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                        </div>
                        <div class="col-md-4 text-center">
                            <h2>Inventory Valuation Report (<?php echo $year; ?>)</h2>
                        </div>
                        <div class="col-md-4 text-right side-nav">
                            <?php echo date( 'm/d/Y h:i a' ) ?><br />
                            <div class="d-print-none">
                                <a href="../index.php" class="btn btn-success">Admin Home</a>&nbsp;&nbsp;
                                <a href="index.php" class="btn btn-primary">Reports Home</a><br />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>CD</th>
                                        <th>Page</th>
                                        <th>Mfg.</th>
                                        <th>Item #</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>List</th>
                                        <th>Ext. List</th>
                                        <th>Sell</th>
                                        <th>Ext. Sell</th>
                                        <th>Cost</th>
                                        <th>Ext. Cost</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $inventory as $row ) { ?>
                                        <?php
                                        if ( $row[ 'cost_code' ] != $costCode ) {
                                            continue;
                                        }
                                        $code = isset( $codes[ $costCode ] ) ? $codes[ $costCode ][ 'code' ] : '';

                                        $value = 0;
                                        if ( !empty( $code ) ) {
                                            $percentage = abs( $codes[ $costCode ][ 'percentage' ] );
                                            $field = $codes[ $costCode ][ 'field' ];

                                            $value = ( $row[ 'quantity' ] * $row[ $field ] ) * $percentage;
                                        }

                                        $costCodes[ $costCode ][ 'count' ]++;
                                        $costCodes[ $costCode ][ 'total_list_price' ] += ( $row[ 'quantity' ] * $row[ 'list_price' ] );
                                        $costCodes[ $costCode ][ 'total_sell_price' ] += ( $row[ 'quantity' ] * $row[ 'sell_price' ] );
                                        $costCodes[ $costCode ][ 'total_cost' ] += ( $row[ 'quantity' ] * $row[ 'cost' ] );
                                        $costCodes[ $costCode ][ 'total_value' ] += $value;
                                        ?>
                                        <tr>
                                            <td><?php echo $code; ?></td>
                                            <td><?php echo $row[ 'page' ]; ?></td>
                                            <td><?php echo $manufacturers[ $row[ 'manufacturer' ] ] ?></td>
                                            <td><?php echo $row[ 'product_id' ]; ?></td>
                                            <td><?php echo $row[ 'product_description' ]; ?></td>
                                            <td><?php echo $row[ 'quantity' ]; ?></td>
                                            <td><?php echo '$' . number_format( $row[ 'list_price' ], 2 ); ?></td>
                                            <td><?php echo '$' . number_format( $row[ 'quantity' ] * $row[ 'list_price' ], 2 ); ?></td>
                                            <td><?php echo '$' . number_format( $row[ 'sell_price' ], 2 ); ?></td>
                                            <td><?php echo '$' . number_format( $row[ 'quantity' ] * $row[ 'sell_price' ], 2 ); ?></td>
                                            <td><?php echo '$' . number_format( $row[ 'cost' ], 2 ); ?></td>
                                            <td><?php echo '$' . number_format( $row[ 'quantity' ] * $row[ 'cost' ], 2 ); ?></td>
                                            <td><?php echo '$' . number_format( $value, 2 ); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="7" class="text-right">Total for cost code '<?php echo $code; ?>'
                                        </td>
                                        <td><?php echo '$' . number_format( $costCodes[ $costCode ][ 'total_list_price' ], 2 ); ?></td>
                                        <td></td>
                                        <td><?php echo '$' . number_format( $costCodes[ $costCode ][ 'total_sell_price' ], 2 ); ?></td>
                                        <td></td>
                                        <td><?php echo '$' . number_format( $costCodes[ $costCode ][ 'total_cost' ], 2 ); ?></td>
                                        <td><?php echo '$' . number_format( $costCodes[ $costCode ][ 'total_value' ], 2 ); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="page-break d-print-none row">&nbsp;</div>
                    <?php $grandTotal .= $costCodes[ $costCode ][ 'total_value' ]; ?>
                <?php } ?>

                <div class="row header mb-4 d-none">
                    <div class="col-md-4">
                        <img src="../../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Inventory Valuation Report (<?php echo $year; ?>)</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <?php echo date( 'm/d/Y h:i a' ) ?><br />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>Cost Code</th>
                                    <th>Row Count</th>
                                    <th>Total List</th>
                                    <th>Total Sell</th>
                                    <th>Total Cost</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ( $costCodes as $costCode => $values ) {
                                    $code = isset( $codes[ $costCode ] ) ? $codes[ $costCode ][ 'code' ] : '';
                                    ?>
                                    <tr>
                                        <td><?php echo $code; ?></td>
                                        <td><?php echo $values[ 'count' ]; ?></td>
                                        <td><?php echo '$' . number_format( $values[ 'total_list_price' ], 2 ); ?></td>
                                        <td><?php echo '$' . number_format( $values[ 'total_sell_price' ], 2 ); ?></td>
                                        <td><?php echo '$' . number_format( $values[ 'total_cost' ], 2 ); ?></td>
                                        <td><?php echo '$' . number_format( $values[ 'total_value' ], 2 ); ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>Grand Total</strong></td>
                                    <td><?php echo '$' . number_format( $grandTotal, 2 ); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

