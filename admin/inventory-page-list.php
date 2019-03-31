<?php

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\InventoryItem;

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$db = new DB();

$pages = get_pages( $year, $db );
$keyers = get_keyers( $year, $db );

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Page List</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../styles/app.css" />

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header mb-4">
                    <div class="col-md-4">
                        <img src="../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Inventory Pages - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="index.php" class="btn-success">Admin Home</a><br />
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-md-12">
                        <ul>
                            <?php if ( !empty( $pages ) ) { ?>
                                <?php foreach ( $pages as $page ) {
                                    $keyer = 'Name Unknown';
                                    if ( isset( $keyers[ $page[ 'keyer' ] ] ) ) {
                                        $keyer = $keyers[ $page[ 'keyer' ] ];
                                    }
                                    echo '<li><a href="page.php?year=' . $year .
                                        '&page=' . $page[ 'page' ] .
                                        '&keyer=' . $page[ 'keyer' ] . '">Page ' . $page[ 'page' ] . ', ' . $keyer . '</a></li>';
                                } ?>
                            <?php } else { ?>
                                <li>No pages added yet. <a href="../add.php?year=<?php echo $year; ?>">Add one</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>