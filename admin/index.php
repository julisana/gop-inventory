<?php

require_once( './../config.php' );

use GOP\Inventory\DB;

$db = new DB();

$years = get_existing_years( $db );

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Pages</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../styles/app.css" />

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header">
                    <div class="col-md-4">
                        <img src="../img/General-Office-Products-Logo.png" alt="logo" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Admin Pages</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <ul>
                            <li>
                                <?php if ( !in_array( date( 'Y' ), $years ) ) { ?>
                                    <a href="start-year.php">Start New Year (<?php echo date( 'Y' ) ?>)</a>
                                <?php } else { ?>
                                    Start New Year (<?php echo date( 'Y' ) ?> Already Started)
                                <?php } ?>
                            </li>

                            <li>
                                <?php if ( in_array( date( 'Y' ), $years ) ) { ?>
                                    <a href="inventory-page-list.php">View Inventory Page List (Current Year)</a>
                                <?php } else { ?>
                                    View Inventory Page List (Current Year Not Started Yet)
                                <?php } ?>
                                <?php if ( !empty( $years ) ) { ?>
                                    <ul>
                                        <?php foreach ( $years as $year ) {
                                            echo '<li><a href="inventory-page-list.php?year=' . $year . '">Previous Year: ' . $year . '</a></li>';
                                        } ?>
                                    </ul>
                                <?php } ?>
                            </li>

                            <li>
                                <?php if ( in_array( date( 'Y' ), $years ) ) { ?>
                                    <a href="keyers-list.php">View Keyers (Current Year)</a>
                                <?php } else { ?>
                                    View Keyers (Current Year Not Started Yet)
                                <?php } ?>
                                <?php if ( !empty( $years ) ) { ?>
                                    <ul>
                                        <?php foreach ( $years as $year ) {
                                            echo '<li><a href="keyers-list.php?year=' . $year . '">Previous Year: ' . $year . '</a></li>';
                                        } ?>
                                    </ul>
                                <?php } ?>
                            </li>

                            <li>
                                <?php if ( in_array( date( 'Y' ), $years ) ) { ?>
                                    <a href="manufacturers-list.php">View Manufacturers (Current Year)</a>
                                <?php } else { ?>
                                    View Manufacturers (Current Year Not Started Yet)
                                <?php } ?>
                                <?php if ( !empty( $years ) ) { ?>
                                    <ul>
                                        <?php foreach ( $years as $year ) {
                                            echo '<li><a href="manufacturers-list.php?year=' . $year . '">Previous Year: ' . $year . '</a></li>';
                                        } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>