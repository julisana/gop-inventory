<?php

require_once( './config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\InventoryItem;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $inventory = new InventoryItem();
    $shared = [
        'location' => $_REQUEST[ 'location' ],
        'year' => $_REQUEST[ 'year' ],
        'page' => $_REQUEST[ 'page' ],
        'keyer' => $_REQUEST[ 'keyer' ],
    ];

    foreach ( $_REQUEST[ 'inventory' ] as $inventoryItem ) {
        $inventoryItem = array_merge( $inventoryItem, $shared );

        $inventory->setDB( $db )->create( $inventoryItem );
    }

    if ( isset( $_REQUEST[ 'save_close' ] ) ) {
        redirect( 'index.php' );
    }

    redirect( 'add.php' );
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$manufacturers = get_manufacturers( $year, $db );

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
                        <img src="img/General-Office-Products-Logo.png" alt="logo" width="200" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Inventory <?php echo $year; ?> - Add Page</h2>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="index.php">Home</a>
                    </div>
                </div>
                <form action="add.php" method="post">
                    <div class="row page">
                        <div class="col-md-2 offset-md-3">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value=""
                                       required />
                                <input type="hidden" name="year" value="<?php echo $year ?>" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="page">Page Number</label>
                                <input type="number" class="form-control" id="page" name="page" value="" required />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="keyer">Keyer</label>
                                <select class="form-control" id="keyer" name="keyer" required>
                                    <option value="">Select One</option>
                                    <?php foreach ( $keyers as $key => $value ) {
                                        echo '<option value="' . $key . '">' . $value . '</option>' . "\n";
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="labels">
                        <?php include( 'inventory-heading.php' ); ?>
                    </div>

                    <div class="inventory-items">
                        <?php include( 'inventory-row.php' ); ?>
                    </div>

                    <div class="row">
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
            $(document).on('click', '.remove-item', function () {
                removeRow(this, 'inventory-item');
                renameRows('inventory-items');
            });

            //Generate a new line
            $('.inventory-items').on('keydown', '.sell-price', function (event) {
                console.log('keyup');
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
