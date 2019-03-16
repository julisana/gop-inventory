<?php

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\Manufacturer;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $manufacturer = new Manufacturer();
    $year = $_REQUEST[ 'year' ];
    $shared = [
        'year' => $year,
    ];

    foreach ( $_REQUEST[ 'manufacturers' ] as $manufacturerItem ) {
        $manufacturerItem = array_merge( $manufacturerItem, $shared );

        try {
            $manufacturer->setDB( $db )->saveOrCreate( $manufacturerItem );
        } catch ( Exception $e ) {
            redirect( 'manufacturers-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    if ( !empty( $_REQUEST[ 'deleteIds' ] ) ) {
        try {
            foreach ( explode( ',', $_REQUEST[ 'deleteIds' ] ) as $deleteId ) {
                $manufacturer->setDb( $db )->delete( $deleteId, $year );
            }
        } catch ( Exception $e ) {
            redirect( 'manufacturers-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    redirect( 'manufacturers-list.php?year=' . $year );
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$manufacturers = $db->table( 'manufacturer' )
    ->where( [ 'year' => $year ] )
    ->select();

$results = $db->table( 'inventory' )
    ->fields( [ 'distinct manufacturer' ] )
    ->where( [ 'year' => $year ] )
    ->select();

$usedItems = [];
foreach ( $results as $result ) {
    $usedItems[] = $result[ 'manufacturer' ];
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Manufacturers List</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../styles/app.css" />

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header">
                    <div class="col-md-4">
                        <img src="../img/General-Office-Products-Logo.png" alt="logo" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Manufacturers List - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="index.php">Admin Home</a><br />
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <?php include( '../errors.php' ); ?>
                <form action="manufacturers-list.php" method="post">
                    <input type="hidden" name="year" value="<?php echo $year ?>" />

                    <div class="labels">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="line">ID</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="line">Code</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="is-new">Name</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="manufacturers">
                        <?php if ( !empty( $manufacturers ) ) { ?>
                            <?php foreach ( $manufacturers as $row ) {
                                include( 'manufacturer-row.php' );
                            } ?>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <input type="hidden" name="deleteIds" id="delete" value="" />
                            <input type="submit" class="form-control btn btn-success" name="save"
                                   value="Save changes" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="../scripts/app.js"></script>
        <script type="text/javascript">
            //Remove the line and record the ID in the delete input
            $(document).on('click', '.btn-danger.remove-item', function () {
                removeRow(this, 'manufacturer-item');
                renameRows('manufacturers');
            });

            //Generate a new line via tab
            $('.manufacturers').on('keydown', '.name', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.name').last().prop('name')) {
                    $('.manufacturers').append(addRow('manufacturer-item'));
                    renameRows('manufacturers');

                    //Show the remove button if there is more than one item in the list
                    if ($('.manufacturer-item').length > 1) {
                        $('.remove-item').removeClass('d-none');
                    }
                }
            });
        </script>
    </body>
</html>
