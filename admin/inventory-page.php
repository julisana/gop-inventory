<?php

/**
 * Add/Update/Delete inventory items for the specified year and page. No calculations are done on this page,
 * but the Cost and Cost Code fields are accessible. Cost code has a default value.
 */

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\InventoryItem;

$db = new DB();

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$page = false;
if ( isset( $_REQUEST[ 'page' ] ) ) {
    $page = $_REQUEST[ 'page' ];
}

$keyer = false;
if ( isset( $_REQUEST[ 'keyer' ] ) ) {
    $keyer = $_REQUEST[ 'keyer' ];
}

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    if ( isset( $_REQUEST[ 'selectPage' ] ) && $page ) {
        redirect( 'inventory-page.php?year=' . $year . '&page=' . $page );
        die;
    }
    else if ( isset( $_REQUEST[ 'selectPage' ] ) && !$page ) {
        redirect( 'inventory-page.php?year=' . $year . '&error=ERRORPAGENOTFOUND' );
    }

    $inventory = new InventoryItem();
    $shared = [
        'location' => $_REQUEST[ 'location' ],
        'year' => $year,
        'page' => $page,
        'keyer' => $keyer,
    ];

    $error = false;

    foreach ( $_REQUEST[ 'inventory' ] as $inventoryItem ) {
        $inventoryItem = array_merge( $inventoryItem, $shared );

        //Don't save a value to cost_code if there's no actual value
        if ( isset( $inventoryItem[ 'cost_code' ] ) && $inventoryItem[ 'cost_code' ] == 0 ) {
            unset( $inventoryItem[ 'cost_code' ] );
        }

        try {
            $inventory->setDB( $db )->saveOrCreate( $inventoryItem );
        } catch ( Exception $e ) {
            redirect( 'inventory-page.php?year=' . $year . '&page=' . $page . '&error=ERRORUPDATE' );
        }
    }

    if ( !empty( $_REQUEST[ 'deleteIds' ] ) ) {
        try {
            foreach ( explode( ',', $_REQUEST[ 'deleteIds' ] ) as $deleteId ) {
                $inventory->setDb( $db )->delete( $deleteId, $year );
            }
        } catch ( Exception $e ) {
            redirect( 'inventory-page.php?year=' . $year . '&page=' . $page . '&error=ERRORUPDATE' );
        }
    }

    if ( $error ) {
        redirect( 'inventory-page.php?year=' . $year . '&page=' . $page . '&error=' . $error );
    }

    redirect( 'inventory-page.php?year=' . $year . '&page=' . $page );
}

$error = '';
if ( isset( $_REQUEST[ 'error' ] ) ) {
    $error = $_REQUEST[ 'error' ];
}

$pages = get_pages( $year, $db );
$keyers = get_keyers( $year, $db );

$items = get_inventory_items( $year, $page, '', $db );
$manufacturers = get_manufacturers( $year, $db );
$costCodes = get_cost_codes( $year, $db );

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Page List</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="../styles/app.css" />

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
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
                        <h2>Edit Inventory Page - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="index.php" class="btn btn-success">Admin Home</a><br />
                        <?php if ( !empty( $pages ) ) { ?>
                            <form action="inventory-page.php" method="post"
                                  class="form-inline d-flex justify-content-end">
                                <input type="hidden" name="year" value="<?php echo $year; ?>" />
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Select Page:</label>
                                    </div>
                                    <select name="page" class="custom-select form-control">
                                        <option value="">Select One</option>
                                        <?php foreach ( $pages as $pageNumber => $keyerId ) {
                                            $name = 'Name Unknown';
                                            if ( isset( $keyers[ $keyerId ] ) ) {
                                                $name = $keyers[ $keyerId ];
                                            }
                                            ?>

                                            <option value="<?php echo $pageNumber; ?>"<?php echo $pageNumber == $page ? ' selected' : '' ?>><?php echo $pageNumber . ', ' . $name ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="year" value="<?php echo $year; ?>" />
                                    <input type="hidden" name="selectPage" value />
                                    <div class="input-group-append">
                                        <input type="submit" class="form-control btn btn-primary" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <?php include( '../errors.php' ); ?>

                <?php if ( $page ) {
                    //All items on a page should have the same location and keyer, so just pull the first one to use it
                    $keys = array_keys( $items );
                    $first = $items[ $keys[ 0 ] ];
                    ?>
                    <form action="inventory-page.php" method="post">
                        <div class="row page">
                            <div class="offset-md-3 col-md-2">
                                <div class="form-group">
                                    <label for="page">Page Number</label>
                                    <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Page Number" data-content="The current page being edited. This field can no longer be altered.">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" class="form-control" id="page" name="page"
                                           value="<?php echo $page; ?>" disabled />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Location" data-content="Please list the location of the page you're working on. Example: Server Room">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" class="form-control" id="location" name="location"
                                           value="<?php echo $first[ 'location' ]; ?>" />
                                    <input type="hidden" name="year" value="<?php echo $year; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="keyer">Keyer</label>
                                    <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Keyer" data-content="Select the keyer's name. If they keyer's name isn't listed, you will need to add it from the Admin Home area.">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </span>
                                    <select class="form-control" id="keyer" name="keyer" required>
                                        <option value="">Select One</option>
                                        <?php foreach ( $keyers as $key => $value ) { ?>
                                            <option value="<?php echo $key; ?>" <?php echo ( $first[ 'keyer' ] == $key ) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="labels">
                            <?php include( '../inventory-heading.php' ); ?>
                        </div>

                        <div class="inventory-items">
                            <?php if ( !empty( $items ) ) { ?>
                                <?php foreach ( $items as $index => $row ) {
                                    include( 'inventory-admin-row.php' );
                                } ?>
                            <?php } ?>
                        </div>

                        <div class="row">&nbsp;</div>

                        <div class="row">
                            <div class="col-md-2">
                                <input type="hidden" name="page" value="<?php echo $page; ?>" />
                                <input type="hidden" name="deleteIds" id="delete" value="" />
                                <input type="submit" class="form-control btn btn-primary" value="Save Changes" />
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>

        <script type="text/javascript" src="../scripts/app.js"></script>
        <script type="text/javascript">
            //Enable all popovers on the page
            $(function () {
                $('[data-toggle="popover"]').popover();
            });

            //Remove the line and record the ID in the delete input
            $(document).on('click', '.remove-item', function () {
                removeRow(this, 'inventory-item');
                renameRows('inventory-items');
            });

            //Generate a new line
            $('.inventory-items').on('keydown', '.cost', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.cost').last().prop('name')) {
                    $('.inventory-items').append(addRow('inventory-item'));
                    renameRows('inventory-items');

                    //Show the remove button if there is more than one item in the list
                    if ($('.inventory-item').length > 1) {
                        $('.remove-item').removeClass('d-none');
                    }
                }
            });
        </script>
    </body>
</html>
