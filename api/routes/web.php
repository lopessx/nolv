<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () {
    return response();
});

// Routes with throttle
$router->group(['middleware' => 'throttle:global'], function () use ($router) {
    // Clients
    $router->post('/client/login', 'ClientController@login');
    $router->post('/client/auth', 'ClientController@auth');
    $router->post('/client/register', 'ClientController@register');
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    // Clients
    $router->post('/logout', 'ClientController@logout');
    $router->delete('/client/delete/{id}', 'ClientController@delete');
    $router->put('/client/update/{id}', 'ClientController@update');

    // Products
    $router->post('/product', 'ProductController@store');
    $router->put('/product/{id}', 'ProductController@update');
    $router->delete('/product/{id}', 'ProductController@delete');
    $router->post('/product/upload', 'ProductController@uploadProduct');
    $router->post('/product/image/upload', 'ProductController@storeImages');
    $router->delete('/product/image/delete/{imgId}', 'ProductController@deleteProductImage');
    $router->get('/products/client/{id}', 'OrderController@getProductsClient');
    $router->get('/product/download/{id}', 'ProductController@downloadProduct');

    // Stores
    $router->delete('/store/{storeId}', 'StoreController@delete');
    $router->get('/store/client/{clientId}', 'StoreController@getClientStore');
    $router->post('/store', 'StoreController@store');
    $router->put('/store/{storeId}', 'StoreController@update');

    // Tickets
    $router->post('/ticket', 'TicketController@store');
    $router->post('/ticket/admin', 'TicketController@sendSupportMessageAdmin');

    // Orders
    $router->get('/order', 'OrderController@get');
    $router->get('/orders/client/{id}', 'OrderController@getOrdersClient');
    $router->get('/order/{id}', 'OrderController@show');
    $router->post('/order', 'OrderController@store');
    $router->put('/order/{id}', 'OrderController@update');
    $router->delete('/order/{id}', 'OrderController@delete');

    // Product Ratings
    $router->get('/rating/product/{pid}/client/{cid}', 'RatingController@getClientRating');
    $router->post('/rating', 'RatingController@store');
    $router->put('/rating/{id}', 'RatingController@update');
    $router->delete('/rating/{id}', 'RatingController@delete');

    // Payment methods
    $router->post('/payment/capture/{orderId}', 'PaymethodController@capturePayment');
});

// Products
$router->get('/products', 'ProductController@get');
$router->get('/product/{id}', 'ProductController@getOne');

// Languages
$router->get('/languages', 'LanguageController@get');

// Operational System
$router->get('/os', 'OsController@get');

// Category
$router->get('/categories', 'CategoryController@get');

// Product Images
$router->get('/product/image/{id}', 'ProductImageController@getOne');

// Product Ratings
$router->get('/ratings/product/{id}', 'RatingController@getProductRatings');

// Payment Methods
$router->get('/payment/list', 'PaymethodController@get');

$router->post('/payment/notify/{orderId}', 'PaymethodController@listenPayment');
