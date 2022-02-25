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

$router->get('/', function () use ($router) {
	return $router->app->version();
});

// Products
$router->get('/products', 'ProductsController@get');
$router->get('/product/{id}', 'ProductsController@getOne');
$router->post('/product', 'ProductsController@store');
$router->put('/product/{id}', 'ProductsController@update');
$router->delete('/product/{id}', 'ProductsController@delete');

// Clients
// $router->get('/category', 'CategoryController@get');

// Stores
// $router->get('/category', 'CategoryController@get');

// Tickets
// $router->get('/category', 'CategoryController@get');

// Sales
// $router->get('/category', 'CategoryController@get');

// Category
$router->get('/category', 'CategoryController@get');

// Product Images
$router->get('/product/image/{id}', 'ProductImagesController@getOne');

// Product Ratings
$router->get('/ratings/product/{id}', 'RatingsController@getProductRatings');
$router->post('/rating', 'RatingsController@store');
