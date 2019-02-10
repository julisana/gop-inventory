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
            $manufacturer->setDB( $db )->save( $manufacturerItem );
        }
        catch (Exception $e) {
            redirect( 'manufacturer-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    redirect( 'manufacturer-list.php?year=' . $year );
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$manufacturers = $db->table( 'manufacturer' )
    ->where( [ 'year' => $year ] )
    ->select();

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
                            <?php foreach ( $manufacturers as $index => $row ) {
                                include( 'manufacturer-row.php' );
                            } ?>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <input type="submit" class="form-control btn btn-success" name="save" value="Save changes" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">
            function addRow(parentClass) {
                var html = $(parentClass).last().html();

                var item = document.createElement('div');
                item.setAttribute('class', 'row manufacturer-item');
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
                $('.manufacturers > div').each(function (key, element) {
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
                var parent = $(this).parents('.manufacturer-item');
                $(parent).remove();
            });

            //Generate a new line
            $('.manufacturers').on('keyup', '.name', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.name').last().prop('name')) {
                    $('.manufacturers').append(addRow('.manufacturer-item'));

                    renameChildren();
                }
            });
        </script>
    </body>
</html>
