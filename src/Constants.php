<?php

namespace SwiatPrzesylek;


class Constants
{
    const PLUGIN_NAME = 'SwiatPrzesylek';
    const ENV_PROD = 'prod';
    const ENV_DEV = 'dev';

    const VOLUME_TYPE_FROM_SHIPPING_PACKAGE = 0;
    const VOLUME_TYPE_FROM_ITEMS_DATA = 1;

    // API attribute config
    const QTY_MODEL_IGNORE_BUNDLE_COMPONENTS = 'qtyModelIgnoreBundleComponents';
    const QTY_MODEL_IGNORE_BUNDLE_ITEM = 'qtyModelIgnoreBundleItem';
}