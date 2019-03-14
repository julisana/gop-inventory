<?php

require_once( './config.php' );

use GOP\Inventory\DB;

$db = new DB();

$years = get_existing_years( $db );

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Line Items</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="styles/app.css" />

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header">
                    <div class="col-md-4">
                        <img src="img/General-Office-Products-Logo.png" alt="logo" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Inventory</h2>
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-md-12">
                        <ul>
                            <li>
                                <?php if ( in_array( date( 'Y' ), $years ) ) { ?>
                                    <a href="add.php">Add Inventory Page (Current Year)</a>
                                <?php } else { ?>
                                    Add Inventory Page (Current Year Not Started Yet)
                                <?php } ?>
                                <?php if ( !empty( $years ) ) { ?>
                                    <ul>
                                        <?php foreach ( $years as $year ) {
                                            if ( $year != date( 'Y' ) ) {
                                                echo '<li><a href="add.php?year=' . $year . '">Previous Year: ' . $year . '</a></li>';
                                            }
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
