<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 06/06/2020
 */

namespace SwiatPrzesylek\Validators;

use Plenty\Validation\Validator;

class StatusMapValidator extends Validator
{
    public static function validate($spStatus, $orderStatus)
    {
        static::validateOrFail([
            'spStatus' => $spStatus,
            'orderStatus' => $orderStatus
        ]);
    }
    protected function defineAttributes()
    {
        $this->addString('spStatus', true);
        $this->addString('orderStatus');
    }
}