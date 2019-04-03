<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-17
 * Time: 20:01
 */

use GOP\Inventory\DB;

if ( !function_exists( 'get_inventory_items' ) ) {
    function get_inventory_items( $year, $page = '', $keyer = '', $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        $where = [ 'year' => $year ];
        if ( !empty( $page ) ) {
            $where[ 'page' ] = $page;
        }
        if ( !empty( $keyer ) ) {
            $where[ 'keyer' ] = $keyer;
        }
        $items = $db->table( 'inventory' )
            ->where( $where )
            ->select();

        return $items;
    }
}

if ( !function_exists( 'get_keyers' ) ) {
    /**
     * Get a list of keyers from the database for the specified year.
     *
     * @param string    $year
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
        $results = $db->table( 'keyer' )
            ->fields( [ 'id', 'name' ] )
            ->where( [ 'year' => $year ] )
            ->select();

        foreach ( $results as $result ) {
            $keyers[ $result[ 'id' ] ] = $result[ 'name' ];
        }

        return $keyers;
    }
}

if ( !function_exists( 'get_manufacturers' ) ) {
    /**
     * Get a list of manufacturers from the database for the specified year.
     *
     * @param string    $year
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
        $results = $db->table( 'manufacturer' )
            ->fields( [ 'id', 'name' ] )
            ->where( [ 'year' => $year ] )
            ->select();

        foreach ( $results as $result ) {
            $manufacturers[ $result[ 'id' ] ] = $result[ 'name' ];
        }

        return $manufacturers;
    }
}

if ( !function_exists( 'get_cost_codes' ) ) {
    /**
     * Get a list of cost codes from the database for the specified year.
     *
     * @param string    $year
     * @param DB|string $db
     *
     * @return array
     */
    function get_cost_codes( $year, $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        $codes = [];
        $results = $db->table( 'cost_code' )
            ->fields( [ 'id', 'name' ] )
            ->where( [ 'year' => $year ] )
            ->select();

        foreach ( $results as $result ) {
            $codes[ $result[ 'id' ] ] = $result[ 'name' ];
        }

        return $codes;
    }
}

if ( !function_exists( 'get_existing_years' ) ) {
    /**
     * Get a list of existing years from the database.
     *
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
        $results = $db->table( 'keyer' )
            ->fields( [ 'distinct year' ] )
            ->orderBy( 'year desc' )
            ->select();

        foreach ( $results as $result ) {
            $years[] = $result[ 'year' ];
        }

        return $years;
    }
}

if ( !function_exists( 'get_pages' ) ) {
    /**
     * Get a list of pages from the database for the specified year.
     *
     * @param string $year
     * @param string $db
     *
     * @return array
     */
    function get_pages( $year, $db = '' )
    {
        if ( !$db instanceof DB ) {
            $db = new DB();
        }

        $pages = [];
        $results = $db->table( 'inventory' )
            ->fields( [ 'distinct page', 'keyer' ] )
            ->where( [ 'year' => $year ] )
            ->orderBy( 'page asc' )
            ->select();

        foreach ( $results as $page ) {
            $pages[ $page[ 'page' ] ] = $page[ 'keyer' ];
        }

        return $pages;
    }
}

if ( !function_exists( 'start_year' ) ) {
    /**
     * Start a new year by copying the data from the previous year.
     *
     * @param string $year
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
            $manufacturers = $db->table( 'manufacturer' )
                ->fields( [ 'code', 'name' ] )
                ->where( [ 'year' => $previousYear ] )
                ->select();
            $keyers = $db->table( 'keyer' )
                ->fields( [ 'code', 'name' ] )
                ->where( [ 'year' => $previousYear ] )
                ->select();
            $costCodes = $db->table( 'cost_code' )
                ->fields( [ 'code', 'name', 'percentage', 'field' ] )
                ->where( [ 'year' => $previousYear ] )
                ->select();

            foreach ( $manufacturers as $manufacturer ) {
                $manufacturer[ 'year' ] = $year;
                $db->table( 'manufacturer' )->fields( $manufacturer )->insert();
            }

            foreach ( $keyers as $keyer ) {
                $keyer[ 'year' ] = $year;
                $db->table( 'keyer' )->fields( $keyer )->insert();
            }

            foreach ( $costCodes as $code ) {
                $code[ 'year' ] = $year;
                $db->table( 'cost_code' )->fields( $code )->insert();
            }
        } catch ( Exception $e ) {
            return false;
        }

        return true;
    }
}
