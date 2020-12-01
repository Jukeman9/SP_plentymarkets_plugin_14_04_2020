<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/06/2020
 */

namespace SwiatPrzesylek\Repositories;


use Plenty\Modules\Order\Status\Contracts\OrderStatusRepositoryContract;
use Plenty\Plugin\Log\Loggable;
use SwiatPrzesylek\Libs\SwiatPrzesylek\Status;

class StatusRepository
{
    use Loggable;

    /**
     * @var OrderStatusRepositoryContract
     */
    private $orderStatusRepositoryContract;

    public function __construct(OrderStatusRepositoryContract $orderStatusRepositoryContract)
    {
        $this->orderStatusRepositoryContract = $orderStatusRepositoryContract;
    }


    public function getOrderStatuses(): array
    {
        $collection = $this->orderStatusRepositoryContract->all();
        $arr = [
            [
                'id' => '',
                'name' => 'NOT SET',
            ],
        ];

        foreach ($collection as $item) {
            $arr[] = [
                'id' => (string)$item->statusId,
                'name' => $item->names->get('en'),
            ];
        }

        return $arr;
    }

    public function getSpStatuses(): array
    {
        return [
            ['id' => Status::NEW_ORDER, 'name' => 'NEW ORDER'],
            ['id' => Status::SHIPMENTS_INJECTED, 'name' => 'SHIPMENTS INJECTED'],
            ['id' => Status::RECEIVED_IN_HUB, 'name' => 'RECEIVED IN HUB'],
            ['id' => Status::IN_TRANSPORTATION, 'name' => 'IN TRANSPORTATION'],
            ['id' => Status::IN_DELIVERY, 'name' => 'IN DELIVERY'],
            ['id' => Status::DELIVERED, 'name' => 'DELIVERED'],
            ['id' => Status::PROBLEM, 'name' => 'PROBLEM'],
            ['id' => Status::RETURN, 'name' => 'RETURN'],
            ['id' => Status::CANCELLED, 'name' => 'CANCELLED'],
        ];
    }
}