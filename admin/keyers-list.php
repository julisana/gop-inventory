<?php

require_once( './../config.php' );

use GOP\Inventory\DB;
use GOP\Inventory\Models\Keyer;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    echo "<pre>";
    print_r( $_REQUEST );
    echo "</pre>";
    $keyer = new Keyer();
    $year = $_REQUEST[ 'year' ];
    $shared = [
        'year' => $year,
    ];

    foreach ( $_REQUEST[ 'keyers' ] as $keyerItem ) {
        $keyerItem = array_merge( $keyerItem, $shared );

        $keyer->setDB( $db )->create( $keyerItem );
    }

    redirect( 'keyers-list.php?year=' . $year );
}

$year = date( 'Y' );
if ( isset( $_REQUEST[ 'year' ] ) ) {
    $year = $_REQUEST[ 'year' ];
}

$keyers = $db->table( 'keyer' )
    ->where( [ 'year' => $year ] )
    ->select();

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
    </head>
    <body>
        <div id="container">
            <div class="content p-5">
                <div class="row header">
                    <div class="col-md-4">
                        <img src="../img/General-Office-Products-Logo.png" alt="logo" />
                    </div>
                    <div class="col-md-4 text-center">
                        <h2>Keyers List - <?php echo $year; ?></h2>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="index.php">Admin Home</a><br />
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

                    <div class="keyers">
                        <?php if ( !empty( $keyers ) ) { ?>
                            <?php foreach ( $keyers as $row ) {
                                include( 'keyer-row.php' );
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
                item.setAttribute('class', 'row keyer-item');
                item.innerHTML = html;

                //If there are any values in any input or textarea, reset them.
                $(item).find('input, select').each(function (key, element) {
                    $(element).val('');
                });

                return item;
            }

            function renameChildren() {
                //Iterate through children and renumber them
                $('.keyers > div').each(function (key, element) {
                    var remove = $(element).find('.remove-item').first();
                    remove.attr('data-row', key);

                    if (key > 0) {
                        remove.removeClass('d-none');
                    }

                    $(element).find('input, select').each(function (inputKey, input) {
                        let name = $(input).attr('name').replace(/\[[\d+]\]/ig, '[' + key + ']');
                        $(input).attr('name', name);
                    });
                });
            }

            $(document).on('click', '.remove-item', function () {
                console.log($(this));
                var parent = $(this).parents('.keyer-item');
                $(parent).remove();
            });

            //Generate a new line
            $('.keyers').on('keyup', '.name', function (event) {
                if (event.key === 'Tab' && $(this).prop('name') === $('.name').last().prop('name')) {
                    $('.keyers').append(addRow('.keyer-item'));

                    renameChildren();
                }
            });
        </script>
    </body>
</html>
