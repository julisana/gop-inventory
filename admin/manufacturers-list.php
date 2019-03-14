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
            redirect( 'manufacturer-list.php?year=' . $year . '&error=ERRORUPDATE' );
        }
    }

    if ( !empty( $_REQUEST[ 'deleteIds' ] ) ) {
        foreach ( explode( ',', $_REQUEST[ 'deleteIds' ] ) as $deleteId ) {
            try {
                $manufacturer->setDb( $db )->delete( $deleteId, $year );
            } catch ( Exception $e ) {
                redirect( 'manufacturer-list.php?year=' . $year . '&error=ERRORUPDATE' );
            }
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
                            <input type="submit" class="form-control btn btn-success" name="save" value="Save changes" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">
            function addRow(parentClass) {
                var html = $(parentClass).last().html();
                var rowId = parseInt($(html).find('.remove-item').first().attr('data-row'), 10) + 1;

                var item = document.createElement('div');
                item.setAttribute('class', 'row manufacturer-item');
                item.innerHTML = html;

                //If there are any values in any input or textarea, reset them.
                $(item).find('input, select').each(function (key, element) {
                    $(element).val('');

                    if ($(element).hasClass('id')) {
                        $(element).remove();
                    }

                    if ($(element).hasClass('code')) {
                        $(element).attr('name', 'manufacturers[' + rowId + '][code]');
                    } else if ($(element).hasClass('name')) {
                        $(element).attr('name', 'manufacturers[' + rowId + '][name]')
                    }
                });

                $(item).find('.remove-item').first().attr('data-row', rowId);

                return item;
            }

            //Remove the line and record the ID in the delete input
            $(document).on('click', '.btn-danger.remove-item', function () {
                var parent = $(this).parents('.manufacturer-item');
                var id = $(parent).find('.id').first().val();

                //Make sure the ID is defined. Newly added rows won't have an ID
                if (typeof id !== 'undefined') {
                    var deleteIds = [];
                    if (document.getElementById('delete').value.length) {
                        deleteIds = document.getElementById('delete').value.split(',');
                    }

                    deleteIds.push(id);
                    document.getElementById('delete').value = deleteIds.join(',');
                }

                $(parent).remove();

                //Hide the remove button if there is only one item in the list
                if (!($('.manufacturer-item').length > 1)) {
                    $('.remove-item').addClass('d-none');
                }
            });

            //Generate a new line via tab
            $('.manufacturers').on('keydown', '.name', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.name').last().prop('name')) {
                    $('.manufacturers').append(addRow('.manufacturer-item'));

                    //Show the remove button if there is more than one item in the list
                    if ($('.manufacturer-item').length > 1) {
                        $('.remove-item').removeClass('d-none');
                    }
                }
            });

            $(function () {
                $('[data-toggle="popover"]').popover({
                    content: 'This manufacturer is associated with an inventory item.',
                    placement: 'right'
                });
            });

            //Hide the popover if the popover box has been clicked
            $(document).on('click', '.popover', function() {
                (($('[data-toggle="popover"]').popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
            });

            //Hide the popover if outside the popover box has been clicked
            $(document).on('click', function (e) {
                $('[data-toggle="popover"],[data-original-title]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
                    }
                });
            });
        </script>
    </body>
</html>
