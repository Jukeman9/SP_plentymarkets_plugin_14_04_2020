<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 15/05/2020
 */

namespace SwiatPrzesylek\EventProcedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Shipping\Returns\Services\RegisterReturnsService;
use Plenty\Plugin\Log\Loggable;
use SwiatPrzesylek\Constants;

class ReturnProcedure
{
    use Loggable;

    /**
     * @param \Plenty\Modules\EventProcedures\Events\EventProceduresTriggered $event
     * @return void
     */
    public function execute(
        EventProceduresTriggered $event,
        RegisterReturnsService $registerReturnsService
    )
    {
        $order = $event->getOrder();
        $registerReturnsService->registerReturns(Constants::PLUGIN_NAME, [$order->id]);
    }
}