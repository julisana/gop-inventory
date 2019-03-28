<?php

require_once( './../../config.php' );

use GOP\Inventory\DB;

$db = new DB();

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$pages = $db->table( 'inventory' )
    ->fields( [ 'distinct page' ] )
    ->where( [ 'year' => $year ] )
    ->orderBy( 'page ASC' )
    ->select();

$offset = 0;

//Split the results in to 3 chunks so they can display in 3 columns
$chunk1 = array_slice( $pages, $offset, count( $pages ) / 3 );
$offset .= count( $chunk1 );
$chunk2 = array_slice( $pages, $offset, count( $pages ) / 3 );
$offset += count( $chunk2 );
$chunk3 = array_slice( $pages, $offset, count( $pages ) / 3 );

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Pages</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../../styles/app.css" />

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header">
                    <div class="col-md-4">
                        <img src="../../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Page Number Report (<?php echo $year; ?>)</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="index.php" class="btn btn-success">Admin Home</a><br />
                        <a href="index.php" class="btn btn-parimary">Reports Home</a><br />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <ul>
                            <?php foreach ( $chunk1 as $row ) { ?>
                                <li><?php echo $row[ 'page' ]; ?></li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul>
                            <?php foreach ( $chunk2 as $row ) { ?>
                                <li><?php echo $row[ 'page' ]; ?></li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul>
                            <?php foreach ( $chunk3 as $row ) { ?>
                                <li><?php echo $row[ 'page' ]; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
