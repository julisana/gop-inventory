<?php

/**
 * Add an inventory item for the specified year.
 */

require_once( './config.php' );

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

$pages = get_pages( $year, $db );

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    if ( isset( $_REQUEST[ 'selectPage' ] ) && $page ) {
        redirect( 'add.php?year=' . $year . '&page=' . $page );
        die;
    }
    else if ( isset( $_REQUEST[ 'selectPage' ] ) && !$page ) {
        redirect( 'inventory-page.php?year=' . $year . '&error=ERRORPAGENOTFOUND' );
    }

    $inventory = new InventoryItem();
    $shared = [
        'location' => $_REQUEST[ 'location' ],
        'year' => $_REQUEST[ 'year' ],
        'page' => $_REQUEST[ 'page' ],
        'keyer' => $_REQUEST[ 'keyer' ],
    ];

    if ( !$page && isset( $pages[ $shared[ 'page' ] ] ) ) {
        redirect( 'add.php?year=' . $year . '&error=ERRORPAGEEXISTS' );
        die;
    }

    foreach ( $_REQUEST[ 'inventory' ] as $inventoryItem ) {
        $inventoryItem = array_merge( $inventoryItem, $shared );

        try {
            $inventory->setDB( $db )->saveOrCreate( $inventoryItem );
        }
        catch ( Exception $exception ) {
            redirect( 'add.php?year=' . $year . '&error=ERRORSAVE' );
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

    if ( isset( $_REQUEST[ 'save_close' ] ) ) {
        redirect( 'index.php' );
    }

    redirect( 'add.php' );
}

$error = '';
if ( isset( $_REQUEST[ 'error' ] ) ) {
    $error = $_REQUEST[ 'error' ];
}

$manufacturers = get_manufacturers( $year, $db );
$keyers = get_keyers( $year, $db );

if ( $page ) {
    $items = get_inventory_items( $year, $page, '', $db );

    $keys = array_keys( $items );
    $first = $items[ $keys[ 0 ] ];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Line Items</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="styles/app.css" />

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
                        <img src="img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Inventory <?php echo $year; ?> - Add Page</h2>
                    </div>
                    <div class="col-md-4 text-right side-nav">
                        <a href="index.php" class="btn btn-success">Home</a><br />
                        <?php if ( !empty( $pages ) ) { ?>
                            <form action="add.php" method="post"
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

                <?php include( 'errors.php' ); ?>

                <form action="add.php" method="post">
                    <div class="row page">
                        <div class="col-md-2 offset-md-3">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Location" data-content="CONTENT"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo $page ? $first[ 'location' ] : ''; ?>" required />
                                <input type="hidden" name="year" value="<?php echo $year ?>" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="page">Page Number</label>
                                <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Page Number" data-content="CONTENT"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                <input type="number" class="form-control" id="page" name="page" value="<?php echo $page ? $first[ 'page' ] : ''; ?>" required />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="keyer">Keyer</label>
                                <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Keyer" data-content="CONTENT"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                <select class="form-control" id="keyer" name="keyer" required>
                                    <option value="">Select One</option>
                                    <?php foreach ( $keyers as $key => $value ) { ?>
                                        <option value="<?php echo $key; ?>" <?php echo ( $page && $first[ 'keyer' ] == $key ) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="labels">
                        <?php include( 'inventory-heading.php' ); ?>
                    </div>

                    <div class="inventory-items">
                        <?php
                        if ( $page ) {
                            foreach ( $items as $row ) {
                                include( 'inventory-row.php' );
                            }
                        }
                        else {
                            include( 'inventory-row.php' );
                        }
                        ?>
                    </div>

                    <br />

                    <div class="row">
                        <input type="hidden" name="deleteIds" id="delete" value="" />

                        <div class="col-md-2">
                            <input type="submit" class="form-control btn btn-success" name="save_new"
                                   value="Save and New Page" />
                        </div>

                        <div class="col-md-2">
                            <input type="submit" class="form-control btn btn-primary" name="save_close"
                                   value="Save and Close" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="scripts/app.js"></script>
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
            $('.inventory-items').on('keydown', '.sell-price', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.sell-price').last().prop('name')) {
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
