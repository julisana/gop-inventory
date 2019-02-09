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

if ( !function_exists( 'get_error' ) ) {
    /**
     * Translate the specified error code into human readable text
     * @param string $errorCode
     *
     * @return string
     */
    function get_error( $errorCode )
    {
        switch ( $errorCode ) {
            case 'ERRORSAVE':
                $error = 'There was a problem adding the record(s)';
            break;
            case 'ERRORUPDATE':
                $error = 'There was a problem updating the record(s).';
            break;
            default:
                $error = 'An error has occurred.';

        }

        return $error;
    }
}
