<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-17
 * Time: 21:00
 */

require_once( './../config.php' );

use GOP\Inventory\DB;

$db = new DB();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $inventory = new InventoryItem();
    $shared = [
        'location' => $_REQUEST[ 'location' ],
        'year' => $_REQUEST[ 'year' ],
        'page' => $_REQUEST[ 'page' ],
        'keyer' => $_REQUEST[ 'keyer' ],
    ];

    redirect( './../admin/index.php' );
}

$years = get_existing_years( $db );

$yearStarted = false;
if ( in_array( date( 'Y' ), $years ) ) {
    $yearStarted = true;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Start New Year</title>
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
                        <h2>Start New Year</h2>
                    </div>
                </div>
<?php if ( !$yearStarted ) { ?>
                <div class="row">
                    <div class="col-md-6 offset-3">
                        Starting a new year will copy the keyers and manufacturers from the previous year so you aren't
                        starting from scratch. You can modify these values to be whatever you need.
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-md-4 offset-4">
                        <form action="start-year.php" method="post">
                            <input type="submit" class="form-control btn btn-success" value="Start New Year" />
                        </form>
                    </div>
                </div>
<?php } else { ?>
                <div class="row">
                    <div class="col-md-4 offset-4 text-center">
                        The current year (<?php echo date( 'Y' ); ?>) has already been started.
                    </div>
                </div>
<?php } ?>
            </div>
        </div>
    </body>
</html>
