<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 12/06/2020
 */

namespace SwiatPrzesylek\Libs\SwiatPrzesylek;


class Status
{
    const NEW_ORDER = 1;
    const SHIPMENTS_INJECTED = 2;
    const RECEIVED_IN_HUB = 10;
    const IN_TRANSPORTATION = 20;
    const IN_DELIVERY = 30;
    const DELIVERED = 40;
    const PROBLEM = 50;
    const RETURN = 60;
    const CANCELLED = 90;
}