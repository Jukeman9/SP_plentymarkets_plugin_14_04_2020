<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/06/2020
 */

namespace SwiatPrzesylek\Contracts;

interface StatusMapRepositoryContract
{
    public function saveMap(string $spStatus, string $orderStatus);

    public function toOrderStatus(string $spStatus);

    public function getMapping(): array;
}