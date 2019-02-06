<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-08
 * Time: 17:01
 */

if ( !function_exists( 'redirect' ) ) {
    /**
     * Redirect the user to a specific location.
     *
     * @param $location
     */
    function redirect( $location )
    {
        header( 'Location: ' . $location );
    }
}
