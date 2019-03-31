<?php

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\CostCode;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $costCode = new CostCode();
    $year = $_REQUEST[ 'year' ];
    $shared = [
        'year' => $year,
    ];

    foreach ( $_REQUEST[ 'codes' ] as $codeItem ) {
        $codeItem = array_merge( $codeItem, $shared );

        try {
            $costCode->setDB( $db )->save( $codeItem );
        } catch ( Exception $e ) {
            redirect( 'cost-code-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    if ( !empty( $_REQUEST[ 'deleteIds' ] ) ) {
        try {
            foreach ( explode( ',', $_REQUEST[ 'deleteIds' ] ) as $deleteId ) {
                $costCode->setDb( $db )->delete( $deleteId, $year );
            }
        } catch ( Exception $e ) {
            redirect( 'cost-code-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    redirect( 'cost-code-list.php?year=' . $year );
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$costCodes = $db->table( 'cost_code' )
    ->where( [ 'year' => $year ] )
    ->select();

$results = $db->table( 'inventory' )
    ->fields( [ 'distinct cost_code' ] )
    ->where( [ 'year' => $year ] )
    ->select();

$usedItems = [];
foreach ( $results as $result ) {
    $usedItems[] = $result[ 'cost_code' ];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Cost Codes List</title>
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
                        <h2>Cost Codes List - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="index.php" class="btn btn-success">Admin Home</a><br />
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <?php include( '../errors.php' ); ?>
                <form action="cost-code-list.php" method="post">
                    <input type="hidden" name="year" value="<?php echo $year ?>" />

                    <div class="labels">
                        <div class="row">
                            <div class="col-md-1">
                                <label for="id">ID</label>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="code">Code</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="percentage">Percentage</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="is_decrease">Increase/Decrease</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="field">Field</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cost-codes">
                        <?php if ( !empty( $costCodes ) ) { ?>
                            <?php foreach ( $costCodes as $index => $row ) {
                                include( 'cost-code-row.php' );
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
                removeRow(this, 'cost-code-item');
                renameRows('cost-codes');
            });

            //Generate a new line via tab
            $('.cost-codes').on('keydown', '.name', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.name').last().prop('name')) {
                    $('.cost-codes').append(addRow('cost-code-item'));
                    renameRows('cost-codes');

                    //Show the remove button if there is more than one item in the list
                    if ($('.cost-code-item').length > 1) {
                        $('.remove-item').removeClass('d-none');
                    }
                }
            });
        </script>
    </body>
</html>
