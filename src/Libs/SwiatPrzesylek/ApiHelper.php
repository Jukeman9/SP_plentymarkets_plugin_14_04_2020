<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/08/2020
 */

namespace SwiatPrzesylek\Libs\SwiatPrzesylek;


class ApiHelper
{
    const CONTENT_ATTR_LIMIT = 255;
    const NOTE1_ATTR_LIMIT = 250;
    const NOTE2_ATTR_LIMIT = 250;

    public static function getAttributeLimit($attribute)
    {
        switch ($attribute) {
            case 'content':
                return self::CONTENT_ATTR_LIMIT;
            case 'note1':
                return self::NOTE1_ATTR_LIMIT;
            case 'note2':
                return self::NOTE2_ATTR_LIMIT;
        }

        return 45;
    }
}