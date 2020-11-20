<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09/06/2020
 */

namespace SwiatPrzesylek\Crons;

use Exception;
use Plenty\Modules\Cron\Contracts\CronHandler;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Modules\Order\Shipping\Information\Contracts\ShippingInformationRepositoryContract;
use Plenty\Modules\Order\Shipping\Package\Contracts\OrderShippingPackageRepositoryContract;
use Plenty\Modules\Order\Shipping\PackageType\Contracts\ShippingPackageTypeRepositoryContract;
use Plenty\Plugin\Log\Loggable;
use SwiatPrzesylek\Constants;
use SwiatPrzesylek\Libs\SwiatPrzesylek\Courier;
use SwiatPrzesylek\Libs\SwiatPrzesylek\HttpClient;
use SwiatPrzesylek\Libs\SwiatPrzesylek\Status;
use SwiatPrzesylek\Repositories\StatusMapRepository;

class UpdateOrderStatus extends CronHandler
{
    use Loggable;

    private $orderRepository;
    private $orderShippingPackageRepository;
    private $shippingPackageTypeRepository;
    private $shippingInformationRepository;
    private $courier;
    private $statusMap;
    private $ignoredStatuses = [
        Status::CANCELLED,
        Status::DELIVERED,
    ];

    public function __construct(
        OrderRepositoryContract $orderRepository,
        OrderShippingPackageRepositoryContract $orderShippingPackageRepository,
        ShippingPackageTypeRepositoryContract $shippingPackageTypeRepository,
        ShippingInformationRepositoryContract $shippingInformationRepository,
        Courier $courier,
        StatusMapRepository $statusMapRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderShippingPackageRepository = $orderShippingPackageRepository;
        $this->shippingPackageTypeRepository = $shippingPackageTypeRepository;
        $this->shippingInformationRepository = $shippingInformationRepository;
        $this->courier = $courier;
        $this->statusMap = $statusMapRepository->getMapping();
    }

    public function handle()
    {
        try {
            $orderIds = $this->findOrderIds();

            // find SP tracking ID
            $orders = [];
            $shipments = [];
            foreach ($orderIds as $orderId) {
                $shippingInformation = $this->shippingInformationRepository->getShippingInformationByOrderId($orderId);
                if (
                    Constants::PLUGIN_NAME === $shippingInformation->shippingServiceProvider &&
                    isset($shippingInformation->additionalData) && is_array($shippingInformation->additionalData)
                ) {
                    foreach ($shippingInformation->additionalData as $shippingPackage) {
                        // packageType - group by api access
                        $shipments[$shippingPackage['packageType']][] = $shippingPackage['packageId'];
                        $orders[(string)$shippingPackage['packageId']] = $orderId;
                    }
                }
            }

            // SP api calls
            // max 20 Ids in one request
            foreach ($shipments as $packageType => $packageIds) {
                foreach (array_chunk($packageIds, 20) as $packageIdsChunk) {
                    $res = $this->courier->track($packageType, $packageIdsChunk);
                    //error handler
                    if (($error = $this->courier->client->getSpApiError())) {
                        throw new Exception(implode(',', array_values($error)));
                    }

                    foreach ($res['response']['tts'] as $packageId => $status) {
                        // package error handler
                        if (HttpClient::RESPONSE_FAIL == $status['result']) {
                            $this->getLogger(Constants::PLUGIN_NAME)
                                ->error('Shipment not found', $status);
                            continue;
                        }

                        $this->updateOrderStatus($orders[$packageId], $status['current_stat_id']);
                    }
                }
            }

        } catch (Exception $exception) {
            $this->getLogger(Constants::PLUGIN_NAME)
                ->error('UpdateOrderStatus.error', [
                    'msg' => $exception->getMessage(),
                ]);
        }

    }

    private function findOrderIds()
    {
        $this->orderRepository->setFilters([
            'createdAtFrom' => date(DATE_W3C, strtotime('-2 weeks')),
        ]);
        $orderIds = [];
        $page = 1;
        do {
            $result = $this->orderRepository->searchOrders($page);
            $orders = $result->getResult();

            foreach ($orders as $order) {
                if (!in_array($order['statusId'], $this->ignoredStatuses)) {
                    $orderIds[$order['id']] = $order['id'];
                }
            }
            $page++;
        } while (!$result->isLastPage());

        return array_unique(array_filter(array_values($orderIds)));
    }

    private function updateOrderStatus($orderId, $spStatus)
    {
        $this->getLogger(Constants::PLUGIN_NAME)
            ->error('updateOrderStatus', [
                '$this->statusMap' => $this->statusMap,
                '$this->statusMap[$spStatus]' => $this->statusMap[$spStatus] ?? null,
            ]);
        try {
            if (isset($this->statusMap[$spStatus])) {
                $order = $this->orderRepository->findOrderById($orderId);
                if ($order && $order->statusId != $this->statusMap[$spStatus]) {
                    $this->orderRepository->updateOrder([
                        'statusId' => $this->statusMap[$spStatus],
                    ], $orderId);
                }
            }
        } catch (Exception $exception) {
            $this->getLogger(Constants::PLUGIN_NAME)
                ->error(Constants::PLUGIN_NAME . '.updateOrderStatus', [
                    'msg' => $exception->getMessage(),
                ]);
            throw $exception;
        }
    }
}