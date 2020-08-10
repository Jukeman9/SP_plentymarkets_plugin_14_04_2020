<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 01/06/2020
 */

namespace SwiatPrzesylek\Providers;


use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

class SwiatPrzesylekRouteServiceProvider extends RouteServiceProvider
{
    public function map(Router $router)
    {
        $router->post('sp-data', 'SwiatPrzesylek\Controllers\StatusController@post');
        $router->get('sp-data', 'SwiatPrzesylek\Controllers\StatusController@get');
    }
}