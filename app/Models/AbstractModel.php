<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-09
 * Time: 21:12
 */

namespace GOP\Inventory\Models;

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

    abstract function create( array $record );

    abstract function save();
}
