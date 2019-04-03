<?php

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\Keyer;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $keyer = new Keyer();
    $year = $_REQUEST[ 'year' ];
    $shared = [
        'year' => $year,
    ];

    foreach ( $_REQUEST[ 'keyers' ] as $keyerItem ) {
        $keyerItem = array_merge( $keyerItem, $shared );

        try {
            $keyer->setDB( $db )->save( $keyerItem );
        } catch ( Exception $e ) {
            redirect( 'keyers-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    if ( !empty( $_REQUEST[ 'deleteIds' ] ) ) {
        try {
            foreach ( explode( ',', $_REQUEST[ 'deleteIds' ] ) as $deleteId ) {
                $keyer->setDb( $db )->delete( $deleteId, $year );
            }
        } catch ( Exception $e ) {
            redirect( 'keyers-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    redirect( 'keyers-list.php?year=' . $year );
}

$error = '';
if ( isset( $_REQUEST[ 'error' ] ) ) {
    $error = $_REQUEST[ 'error' ];
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$keyers = $db->table( 'keyer' )
    ->where( [ 'year' => $year ] )
    ->orderBy( 'name asc' )
    ->select();

$results = $db->table( 'inventory' )
    ->fields( [ 'distinct keyer' ] )
    ->where( [ 'year' => $year ] )
    ->select();

$usedItems = [];
foreach ( $results as $result ) {
    $usedItems[] = $result[ 'keyer' ];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Keyers List</title>
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
                <div class="row header mb-4">
                    <div class="col-md-4">
                        <img src="../img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Keyers List - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="index.php" class="btn btn-success">Admin Home</a><br />
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <?php include( '../errors.php' ); ?>

                <form action="keyers-list.php" method="post">
                    <input type="hidden" name="year" value="<?php echo $year ?>" />

                    <div class="labels">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="code">Code</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="keyers">
                        <?php if ( !empty( $keyers ) ) { ?>
                            <?php foreach ( $keyers as $index => $row ) {
                                include( 'keyer-row.php' );
                            } ?>
                        <?php } else {
                            include( 'keyer-row.php' );
                        } ?>
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
                removeRow(this, 'keyer-item');
                renameRows('keyers');
            });

            //Generate a new line via tab
            $('.keyers').on('keydown', '.name', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.name').last().prop('name')) {
                    $('.keyers').append(addRow('keyer-item'));
                    renameRows('keyers');

                    //Show the remove button if there is more than one item in the list
                    if ($('.keyer-item').length > 1) {
                        $('.remove-item').removeClass('d-none');
                    }
                }
            });
        </script>
    </body>
</html>
