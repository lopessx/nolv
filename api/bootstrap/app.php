<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
	dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
	dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
	Illuminate\Contracts\Debug\ExceptionHandler::class,
	App\Exceptions\Handler::class
);

$app->singleton(
	Illuminate\Contracts\Console\Kernel::class,
	App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');
$app->configure('mail');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
	'auth' => App\Http\Middleware\Authenticate::class,
]);

$app->middleware([
	App\Http\Middleware\CorsMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
	'namespace' => 'App\Http\Controllers',
], function ($router) {
	require __DIR__ . '/../routes/web.php';
});

/**
 * Register generator artisan commands
 *
 * key:generate         Set the application key
 *
 * make:cast            Create a new custom Eloquent cast class
 * make:channel         Create a new channel class
 * make:command         Create a new Artisan command
 * make:controller      Create a new controller class
 * make:event           Create a new event class
 * make:exception       Create a new custom exception class
 * make:factory         Create a new model factory
 * make:job             Create a new job class
 * make:listener        Create a new event listener class
 * make:mail            Create a new email class
 * make:middleware      Create a new middleware class
 * make:migration       Create a new migration file
 * make:model           Create a new Eloquent model class
 * make:notification    Create a new notification class
 * make:pipe            Create a new pipe class
 * make:policy          Create a new policy class
 * make:provider        Create a new service provider class
 * make:request         Create a new form request class
 * make:resource        Create a new resource
 * make:rule            Create a new rule
 * make:seeder          Create a new seeder class
 * make:test            Create a new test class
 *
 * notifications:table  Create a migration for the notifications table
 *
 * schema:dump          Dump the given database schema
 */
$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);

/**
 * Cors http option request catch
 */
$app->register(App\Providers\CatchAllOptionsRequestsProvider::class);

/**
 * Mail Provider configuration and lib loading
 */
$app->register(Illuminate\Mail\MailServiceProvider::class);

$app->alias('mail.manager', Illuminate\Mail\MailManager::class);
$app->alias('mail.manager', Illuminate\Contracts\Mail\Factory::class);

$app->alias('mailer', Illuminate\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);

return $app;
