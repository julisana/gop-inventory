<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-02-09
 * Time: 19:42
 */

namespace GOP\Inventory\Models;

class Manufacturer extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'manufacturer';

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}
