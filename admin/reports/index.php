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
                                    <th width="25%" class="text-center">Page Number Report</th>
                                    <th width="25%" class="text-center">Count Sheet Verification Report</th>
                                    <th width="25%" class="text-center">Update Valuation Field</th>
                                    <th width="25%" class="text-center">Inventory Valuation Report</th>
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
                                            <a href="update-valuation.php" data-toggle="modal" data-target="#update-valuation" data-year="<?php echo date( 'Y' ) ?>"><?php echo date( 'Y' ) ?> (Current Year)</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="inventory-valuation.php"><?php echo date( 'Y' ) ?> (Current Year)</a>
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

                                        echo '<td class="text-center"><a href="page-number.php?year=' . $year . '">' . $year . '</a></td>';
                                        echo '<td class="text-center"><a href="count-sheet-verification.php?year=' . $year . '">' . $year . '</a></td>';
                                        echo '<td class="text-center"><a href="update-valuation.php?year=' . $year . '" data-toggle="modal" data-target="#update-valuation" data-year="' . $year . '">' . $year . '</a></td>';
                                        echo '<td class="text-center"><a href="inventory-valuation.php?year=' . $year . '">' . $year . '</a></td>';
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

        <div class="modal" tabindex="-1" role="dialog" id="update-valuation" aria-labelledby="update-valuation" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Valuation Field (<span class="year"></span>)</h5>
                    </div>
                    <div class="modal-body">
                        <p>
                            You are about to run an update that will modify data for the specified year.
                        </p>

                        <p>
                            Are you sure you want to update the valuation field for <span class="year"></span>?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="update-valuation.php" method="get">
                            <input type="hidden" name="year" value="" />
                            <input type="submit" class="btn btn-danger" value="Update" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $('#update-valuation').on('show.bs.modal', function (event) {
                var year = $(event.relatedTarget).data('year') //get the specified year

                $(this).find('.year').text(year)
                $(this).find('.modal-footer input[name=year]').val(year);
                $(this).find('.modal-footer input').val(year)
                $(this).find('.modal-footer input[type=submit]').val('Update ' + year)
            })
        </script>
    </body>
</html>
