<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-01-09
 * Time: 21:14
 */

namespace GOP\Inventory\Models;

class InventoryItem extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'inventory';

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}