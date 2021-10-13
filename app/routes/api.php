<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('/shipment-reserve', ['uses' => 'YandexMarket@shipmentReserve']);
$router->options('/shipment-reserve', function () {
});

$router->post('/stats-orders', ['uses' => 'YandexMarket@statsOrders']);
$router->options('/stats-orders', function () {
});

$router->post('/stocks', ['uses' => 'YandexMarket@itemsStocks']);
$router->options('/stocks', function () {
});

$router->post('/update-offer', ['uses' => 'YandexMarket@updateOfferMapping']);
$router->options('/update-offer', function () {
});
