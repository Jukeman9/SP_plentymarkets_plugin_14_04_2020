<?php

namespace SwiatPrzesylek\Providers;


use Plenty\Modules\Cron\Services\CronContainer;
use Plenty\Modules\EventProcedures\Services\Entries\ProcedureEntry;
use Plenty\Modules\EventProcedures\Services\EventProceduresService;
use Plenty\Modules\Order\Shipping\Returns\Services\ReturnsServiceProviderService;
use Plenty\Modules\Order\Shipping\ServiceProvider\Services\ShippingServiceProviderService;
use Plenty\Plugin\ServiceProvider;
use SwiatPrzesylek\Constants;
use SwiatPrzesylek\Contracts\StatusMapRepositoryContract;
use SwiatPrzesylek\Controllers\ShippingController;
use SwiatPrzesylek\Crons\UpdateOrderStatus;
use SwiatPrzesylek\EventProcedures\ReturnProcedure;
use SwiatPrzesylek\Repositories\StatusMapRepository;

class SwiatPrzesylekServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        // add REST routes by registering a RouteServiceProvider if necessary
        $this->getApplication()->register(SwiatPrzesylekRouteServiceProvider::class);
        // bind contract to class
        $this->getApplication()->bind(StatusMapRepositoryContract::class, StatusMapRepository::class);
    }

    public function boot(
        CronContainer $cronContainer,
        ShippingServiceProviderService $shippingServiceProviderService,
        ReturnsServiceProviderService $returnsServiceProviderService,
        EventProceduresService $eventProceduresService
    )
    {
        $cronContainer->add(CronContainer::EVERY_FIVE_MINUTES, UpdateOrderStatus::class);

        $shippingServiceProviderService->registerShippingProvider(
            Constants::PLUGIN_NAME,
            [
                'en' => 'Świat Przesyłek',
                'de' => 'Świat Przesyłek',
            ],
            [
                'SwiatPrzesylek\\Controllers\\ShippingController@registerShipments',
                'SwiatPrzesylek\\Controllers\\ShippingController@deleteShipments',
                'SwiatPrzesylek\\Controllers\\ShippingController@getLabels',
            ]
        );

        $returnsServiceProviderService->registerReturnsProvider(
            Constants::PLUGIN_NAME,
            'Świat Przesyłek',
            ShippingController::class
        );

        $eventProceduresService->registerProcedure(
            'SPReturn',
            ProcedureEntry::EVENT_TYPE_ORDER,
            ['de' => 'SP Return', 'en' => 'SP Return'],
            ReturnProcedure::class
        );

    }
}