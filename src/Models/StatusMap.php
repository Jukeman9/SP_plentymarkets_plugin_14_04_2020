<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/06/2020
 */

namespace SwiatPrzesylek\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

class StatusMap extends Model
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $spStatus = '';

    /**
     * @var string
     */
    public $orderStatus = '';

    /**
     * @var int
     */
    public $createdAt = 0;

    /**
     * @var int
     */
    public $updatedAt = 0;

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'SwiatPrzesylek::StatusMap';
    }
}