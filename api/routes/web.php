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

// Products
$router->get('/products', 'ProductController@get');
$router->get('/product/{id}', 'ProductController@getOne');
$router->post('/product', 'ProductController@store');
$router->put('/product/{id}', 'ProductController@update');
$router->delete('/product/{id}', 'ProductController@delete');

// Clients
$router->post('/client/register', 'ClientController@register');
$router->post('/client/login', 'ClientController@login');
$router->post('/client/auth', 'ClientController@auth');
$router->post('/logout', 'ClientController@logout');

// Stores
$router->delete('/store/{storeId}', 'StoreController@delete');
$router->get('/store/client/{clientId}', 'StoreController@getClientStore');
$router->post('/store', 'StoreController@store');
$router->put('/store/{storeId}', 'StoreController@update');

// Tickets
// $router->get('/category', 'CategoryController@get');

// Orders
// $router->get('/category', 'CategoryController@get');

// Category
$router->get('/category', 'CategoryController@get');

// Product Images
$router->get('/product/image/{id}', 'ProductImageController@getOne');

// Product Ratings
$router->get('/ratings/product/{id}', 'RatingController@getProductRatings');
$router->post('/rating', 'RatingController@store');

// Payment Methods
$router->get('/payment/list', 'PaymethodController@get');
$router->post('/payment/order', 'PaymethodController@proccessPayment');
$router->post('/payment/capture/{orderId}', 'OrderController@capturePayment');
