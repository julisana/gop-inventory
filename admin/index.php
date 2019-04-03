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

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header mb-4">
                    <div class="col-md-4">
                        <img src="../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Admin Pages</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <?php if ( !in_array( date( 'Y' ), $years ) ) { ?>
                            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#new-year">Start New Year (<?php echo date( 'Y' ) ?>)</a>
                        <?php } else { ?>
                            <span class="btn btn-success disabled">Start New Year (<?php echo date( 'Y' ) ?> Already Started)</span>
                        <?php } ?><br />
                        <a href="reports/index.php" class="btn btn-primary">All Reports</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width="25%" class="text-center">View Inventory Page List</th>
                                    <th width="25%" class="text-center">View Keyers</th>
                                    <th width="25%" class="text-center">View Manufacturers</th>
                                    <th width="25%" class="text-center">Cost Codes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( in_array( date( 'Y' ), $years ) ) { ?>
                                    <tr>
                                        <td class="text-center">
                                            <a href="inventory-page.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="keyers-list.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="manufacturers-list.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="cost-code-list.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php
                                if ( count( $years ) > 1 ) {
                                    echo '<tr>';
                                    foreach ( $years as $year ) {
                                        if ( $year == date( 'Y' ) ) {
                                            continue;
                                        }

                                        echo '<td class="text-center"><a href="inventory-page.php?year=' . $year . '">' . $year . '</a></td>';
                                        echo '<td class="text-center"><a href="keyers-list.php?year=' . $year . '">' . $year . '</a></td>';
                                        echo '<td class="text-center"><a href="manufacturers-list.php?year=' . $year . '">' . $year . '</a></td>';
                                        echo '<td class="text-center"><a href="cost-code-list.php?year=' . $year . '">' . $year . '</a></td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="new-year" aria-labelledby="new-year" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Start New Year (<?php echo date( 'Y' ); ?>)</h5>
                    </div>
                    <div class="modal-body">
                        <p>
                            Starting a new year will copy the keyers, manufacturers, and cost codes from the previous year
                            so you aren't starting from scratch. You can modify these values to be whatever you need.
                        </p>

                        <p>
                            Are you sure you want to start the <?php echo date( 'Y' ); ?> year?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="start-year.php" method="post">
                            <input type="submit" class="btn btn-danger" value="Start <?php echo date( 'Y' ); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>