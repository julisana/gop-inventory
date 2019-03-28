<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 2019-02-09
 * Time: 12:47
 */

namespace GOP\Inventory\Models;

class CostCode extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'cost_code';

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}
