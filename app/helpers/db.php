<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-17
 * Time: 20:01
 */

use GOP\Inventory\DB;

if ( !function_exists( 'get_keyers' ) ) {
    /**
     * @param string $year
     * @param DB|string $db
     *
     * @return array
     */
    function get_keyers( $year, $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        $keyers = [];
        $results = $db->table( 'keyer' )->fields( [ 'id', 'name' ] )->where( [ 'year' => $year ] )->select();

        foreach ( $results as $result ) {
            $keyers[ $result[ 'id' ] ] = $result[ 'name' ];
        }

        return $keyers;
    }
}

if ( !function_exists( 'get_manufacturers' ) ) {
    /**
     * @param string $year
     * @param DB|string $db
     *
     * @return array
     */
    function get_manufacturers( $year, $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        $manufacturers = [];
        $results = $db->table( 'manufacturer' )->fields( [ 'id', 'name' ] )->where( [ 'year' => $year ] )->select();

        foreach ( $results as $result ) {
            $manufacturers[ $result[ 'id' ] ] = $result[ 'name' ];
        }

        return $manufacturers;
    }
}

if ( !function_exists( 'get_existing_years' ) ) {
    /**
     * @param DB|string $db
     *
     * @return array
     */
    function get_existing_years( $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        $years = [];
        $results = $db->table( 'inventory' )->fields( [ 'distinct year' ] )->select();

        foreach ( $results as $result ) {
            $years[] = $result[ 'year' ];
        }

        return $years;
    }
}

if ( !function_exists( 'start_year' ) ) {
    /**
     * @param        $year
     * @param string $db
     *
     * @return bool
     * @throws Exception
     */
    function start_year( $year, $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        //If the specified year already exists, return
        $years = get_existing_years( $db );
        if ( in_array( $year, $years ) ) {
            return false;
        }

        try {
            $previousYear = $year - 1;
            $manufacturers = $db->table( 'manufacturer' )->fields( [ 'code', 'name' ] )->where( [ 'year' => $previousYear ] )->select();
            $keyers = $db->table( 'keyer' )->fields( [ 'code', 'name' ] )->where( [ 'year' => $previousYear ] )->select();

            foreach ( $manufacturers as $manufacturer ) {
                $manufacturer[ 'year' ] = $year;
                $db->table( 'manufacturer' )->fields( $manufacturer )->insert();
            }

            foreach ( $keyers as $keyer ) {
                $keyer[ 'year' ] = $year;
                $db->table( 'keyer' )->fields( $keyer )->insert();
            }
        }
        catch ( Exception $e ) {
            return false;
        }

        return true;
    }
}
