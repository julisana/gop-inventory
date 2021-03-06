<?php

require_once( './../../config.php' );

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
        <link rel="stylesheet" type="text/css" href="../../styles/app.css" />

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header mb-4">
                    <div class="col-md-4">
                        <img src="../../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Reports</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="../index.php" class="btn btn-success">Admin Home</a><br />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width="33%" class="text-center">Page Number Report</th>
                                    <th width="33%" class="text-center">Count Sheet Verification Report</th>
                                    <th width="33%" class="text-center">Inventory Valuation Report</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( in_array( date( 'Y' ), $years ) ) { ?>
                                    <tr>
                                        <td class="text-center">
                                            <a href="page-number.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="count-sheet-verification.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="inventory-valuation.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php
                                foreach ( $years as $year ) {
                                    if ( $year == date( 'Y' ) ) {
                                        continue;
                                    }

                                    echo '<tr>';
                                    echo '<td class="text-center"><a href="page-number.php?year=' . $year . '">' . $year . '</a></td>';
                                    echo '<td class="text-center"><a href="count-sheet-verification.php?year=' . $year . '">' . $year . '</a></td>';
                                    echo '<td class="text-center"><a href="inventory-valuation.php?year=' . $year . '">' . $year . '</a></td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
