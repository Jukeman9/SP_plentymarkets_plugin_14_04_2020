<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/06/2020
 */

namespace SwiatPrzesylek\Migrations;


use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use SwiatPrzesylek\Models\StatusMap;

class CreateStatusMapTable
{
    /**
     * @param Migrate $migrate
     */
    public function run(Migrate $migrate)
    {
        $migrate->createTable(StatusMap::class);
    }
}