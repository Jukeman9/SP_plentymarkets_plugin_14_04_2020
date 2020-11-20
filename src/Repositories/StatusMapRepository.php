<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/06/2020
 */

namespace SwiatPrzesylek\Repositories;

use Plenty\Exceptions\ValidationException;
use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use SwiatPrzesylek\Contracts\StatusMapRepositoryContract;
use SwiatPrzesylek\Models\StatusMap;
use SwiatPrzesylek\Validators\StatusMapValidator;

class StatusMapRepository implements StatusMapRepositoryContract
{
    private $database;

    public function __construct(DataBase $database)
    {
        $this->database = $database;
    }

    /**
     * @return array [SP Status => Order status]
     */
    public function getMapping(): array
    {
        /** @var StatusMap[] $maps */
        $maps = $this->database->query(StatusMap::class)
            ->get();
        $arr = [];
        foreach ($maps as $map) {
            $arr[$map->spStatus] = $map->orderStatus;
        }

        return $arr;
    }

    public function saveMap(string $spStatus, string $orderStatus)
    {
        try {
            StatusMapValidator::validate($spStatus, $orderStatus);
        } catch (ValidationException $e) {
            throw $e;
        }

        $map = $this->getMap($spStatus);

        if ($map) {
            $map->orderStatus = $orderStatus;
            $map->updatedAt = time();
        } else {
            $map = pluginApp(StatusMap::class);
            $map->spStatus = $spStatus;
            $map->orderStatus = $orderStatus;
            $map->createdAt = time();
            $map->updatedAt = time();
        }
        $this->database->save($map);

        return $map;
    }

    public function toOrderStatus(string $spStatus)
    {
        $map = $this->getMap($spStatus);
        if ($map) {
            return $map->orderStatus;
        }

        return null;
    }

    /**
     * @param string $spStatus
     * @return null|StatusMap
     */
    protected function getMap(string $spStatus)
    {
        $maps = $this->database->query(StatusMap::class)
            ->where('spStatus', $spStatus)
            ->get();

        return $maps['0'] ?? null;
    }
}