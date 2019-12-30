<?php if ( !empty( $error ) ) { ?>
    <div class="alert alert-danger" role="alert">
        <?php
            echo get_error( $error );
            if ( isset( $_REQUEST[ 'error_message' ] ) && !empty( $_REQUEST[ 'error_message' ] ) ) {
                echo ' ' . $_REQUEST[ 'error_message' ];
            }
        ?>

    </div>

    <div class="row">&nbsp;</div>
<?php } ?>
