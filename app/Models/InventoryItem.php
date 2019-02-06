<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-09
 * Time: 21:14
 */

namespace GOP\Inventory\Models;

use Exception;

class InventoryItem extends AbstractModel
{
    /**
     * @param array $record
     *
     * @return bool
     *
     * @throws Exception
     */
    public function create( array $record )
    {
        try {
            $this->db->fields( $record )->table( 'inventory' )->insert();

            return true;
        } catch ( Exception $exception ) {

        }

        return false;
    }

    public function save()
    {
    }
}