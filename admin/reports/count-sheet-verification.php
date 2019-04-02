<?php

require_once( './../../config.php' );

use GOP\Inventory\DB;

$db = new DB();

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$pages = [];
$manufacturers = [];
$inventory = [];
$page = false;
if ( isset( $_REQUEST[ 'page' ] ) ) {
    $page = $_REQUEST[ 'page' ];

    $inventory = $db->table( 'inventory' )
        ->where( [ 'year' => $year, 'page' => $page ] )
        ->orderBy( 'line_number ASC' )
        ->select();

    $rows = $db->table( 'manufacturer' )
        ->fields( [ 'id', 'code' ] )
        ->where( [ 'year' => $year ] )
        ->select();
    foreach ( $rows as $row ) {
        $manufacturers[ $row[ 'id' ] ] = $row[ 'code' ];
    }
}

$pages = $db->table( 'inventory' )
    ->fields( [ 'distinct page' ] )
    ->where( [ 'year' => $year ] )
    ->orderBy( 'page ASC' )
    ->select();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Pages</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../../styles/app.css" />

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
                        <h2>Page Number Report (<?php echo $year; ?>)</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav d-print-none">
                        <a href="index.php" class="btn btn-success">Admin Home</a><br />
                        <a href="index.php" class="btn btn-primary">Reports Home</a><br />
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="offset-md-4 col-md-4">
                        <form action="count-sheet-verification.php" method="get" class="form-inline">
                            <input type="hidden" name="year" value="<?php echo $year; ?>" />
                            <label>Select Page:</label>&nbsp;&nbsp;
                            <select name="page" class="form-control">
                                <?php foreach ( $pages as $row ) { ?>
                                    <option value="<?php echo $row[ 'page' ] ?>"<?php echo $row[ 'page' ] == $page ? ' selected' : '' ?>><?php echo $row[ 'page' ] ?></option>
                                <?php } ?>
                            </select>&nbsp;&nbsp;
                            <input type="submit" class="form-control btn btn-primary" value="Submit" />
                        </form>
                    </div>
                </div>

                <?php if ( $page ) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Line #</th>
                                        <th>Location</th>
                                        <th>New/Used</th>
                                        <th>Mfg.</th>
                                        <th>Product Number</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>List $</th>
                                        <th>Sell $</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $inventory as $row ) { ?>
                                        <tr>
                                            <td><?php echo $row[ 'line_number' ]; ?></td>
                                            <td><?php echo $row[ 'location' ]; ?></td>
                                            <td><?php echo $row[ 'is_new' ] ? 'NEW' : 'USED'; ?></td>
                                            <td><?php echo $manufacturers[ $row[ 'manufacturer' ] ] ?></td>
                                            <td><?php echo $row[ 'product_id' ]; ?></td>
                                            <td><?php echo $row[ 'product_description' ]; ?></td>
                                            <td><?php echo $row[ 'quantity' ]; ?></td>
                                            <td><?php echo $row[ 'list_price' ]; ?></td>
                                            <td><?php echo $row[ 'sell_price' ]; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>
