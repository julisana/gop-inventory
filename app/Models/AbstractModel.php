<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-09
 * Time: 21:12
 */

namespace GOP\Inventory\Models;

use Exception;
use GOP\Inventory\DB;

abstract class AbstractModel
{
    /**
     * @var DB
     */
    protected $db;

    /**
     * @param DB $db
     *
     * @return $this
     */
    public function setDB( DB $db )
    {
        $this->db = $db;

        return $this;
    }

    abstract public function getTable();

    /**
     * Add a new inventory item to the database.
     *
     * @param array $record
     *
     * @return bool
     *
     * @throws Exception
     */
    public function create( array $record )
    {
        $this->db->fields( $record )
            ->table( $this->getTable() )
            ->insert();

        return true;
    }

    /**
     * Update an existing inventory item in the database.
     *
     * @param array $record
     *
     * @return bool
     *
     * @throws Exception
     */
    public function save( array $record )
    {
        //Pull the ID out of the record
        if ( !isset( $record[ 'id' ] ) ) {
            throw new Exception( 'No ID Found.' );
        }

        $id = $record[ 'id' ];
        unset( $record[ 'id' ] );

        $this->db->fields( $record )
            ->table( $this->getTable() )
            ->where( [ 'id' => $id ] )
            ->limit( 1 )
            ->update();

        return true;
    }
}
