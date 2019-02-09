<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-02-09
 * Time: 12:47
 */

namespace GOP\Inventory\Models;

class Keyer extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'keyer';

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}