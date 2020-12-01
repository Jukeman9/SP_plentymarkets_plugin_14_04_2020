<?php

namespace SwiatPrzesylek\Libs;

use Plenty\Modules\Order\Shipping\PackageType\Models\ShippingPackageType;

class PackageTypeHelper
{
    const DEFAULT_TYPE = 'SPA-Standard';

    const PREFIX_A = 'SPA-';
    const PREFIX_B = 'SPB-';
    const PREFIX_C = 'SPC-';

    const SUFFIX_A = '';
    const SUFFIX_B = 'B';
    const SUFFIX_C = 'C';

    public static function getPrefix($packageType)
    {
        return substr(trim($packageType), 0, 4);
    }

    public static function getSuffix($packageType)
    {
        switch (static::getPrefix($packageType)) {
            case self::PREFIX_C:
                return self::SUFFIX_C;
            case self::PREFIX_B:
                return self::SUFFIX_B;
            case self::PREFIX_A:
            default:
                return self::SUFFIX_A;
        }
    }
}