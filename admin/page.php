<?php

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\InventoryItem;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $year = $_REQUEST[ 'year' ];
    $page = $_REQUEST[ 'page' ];
    $keyer = $_REQUEST[ 'keyer' ];

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

        try {
            $inventory->setDB( $db )->save( $inventoryItem );
        }
        catch (Exception $e) {
            redirect( 'page.php?year=' . $year . '&page=' . $page . '&keyer=' . $keyer . '&error=ERRORUPDATE' );
        }
    }

    if ( $error ) {
        redirect( 'page.php?year=' . $year . '&page=' . $page . '&keyer=' . $keyer . '&error=' . $error );
    }

    redirect( 'page.php?year=' . $year . '&page=' . $page . '&keyer=' . $keyer );
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$page = 1;
if ( isset( $_REQUEST[ 'page' ] ) ) {
    $page = $_REQUEST[ 'page' ];
}

$keyer = 1;
if ( isset( $_REQUEST[ 'keyer' ] ) ) {
    $keyer = $_REQUEST[ 'keyer' ];
}

$errors = '';
if ( isset( $_REQUEST[ 'error' ] ) ) {
    $error = $_REQUEST[ 'error' ];
}

$keyers = get_keyers( $year, $db );
$items = get_inventory_items( $year, $page, $keyer, $db );
$manufacturers = get_manufacturers( $year, $db );

//All items on a page should have the same location, so just pull the first one to use it
$keys = array_keys( $items );
$first = $items[ $keys[ 0 ] ];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Page List</title>
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
                        <h2>Edit Inventory Page - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="index.php">Admin Home</a><br />
                        <a href="inventory-page-list.php?year=<?php echo $year; ?>"><?php echo $year; ?> Page List</a>
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <?php include( '../errors.php' ); ?>

                <form action="page.php" method="post">
                    <div class="row page">
                        <div class="col-md-2 offset-md-3">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo $first[ 'location' ]; ?>" required />
                                <input type="hidden" name="year" value="<?php echo $year; ?>" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="page">Page Number</label>
                                <input type="number" class="form-control" id="page" name="page" value="<?php echo $page; ?>" required />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="keyer">Keyer</label>
                                <select class="form-control" id="keyer" name="keyer" required>
                                    <option value="">Select One</option>
                                    <?php foreach ( get_keyers( $year, $db ) as $key => $value ) {
                                        echo '<option value="' . $key . '"' . ( $keyer == $key ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="labels">
                        <?php include( '../inventory-heading.php' ); ?>
                    </div>

                    <div class="inventory-items">
                        <?php if ( !empty( $items ) ) { ?>
                            <?php foreach ( $items as $index => $row ) {
                                include( '../inventory-row.php' );
                            } ?>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <input type="submit" class="form-control btn btn-primary" value="Save Changes" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">
            function addRow(parentClass) {
                var html = $(parentClass).last().html();

                var item = document.createElement('div');
                item.setAttribute('class', 'row inventory-item');
                item.innerHTML = html;

                //If there are any values in any input or textarea, reset them.
                $(item).find('input, select').each(function (key, element) {
                    $(element).val('');

                    if ($(element).hasClass('id')) {
                        $(element).remove();
                    }
                });

                return item;
            }

            function renameChildren() {
                //Iterate through children and renumber them
                $('.inventory-items > div').each(function (key, element) {
                    var remove = $(element).find('.remove-item').first();
                    remove.attr('data-row', key);

                    if (key > 0) {
                        remove.removeClass('d-none');
                    }

                    $(element).find('input, select').each(function (inputKey, input) {
                        var name = $(input).attr('name').replace(/\[[\d]+\]/ig, '[' + key + ']');
                        $(input).attr('name', name);
                    });
                });
            }

            $(document).on('click', '.remove-item', function () {
                var parent = $(this).parents('.inventory-item');
                $(parent).remove();
            });

            //Generate a new line
            $('.inventory-items').on('keyup', '.sell-price', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.sell-price').last().prop('name')) {
                    $('.inventory-items').append(addRow('.inventory-item'));

                    renameChildren();
                }
            });
        </script>
    </body>
</html>