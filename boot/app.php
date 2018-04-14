<?php 

use Respect\Validation\Validator as v;

session_start();

require '../vendor/autoload.php';
$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
		'db' => [
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'workshop',
			'username' => 'root',
			'password' => '',
			'charset' => 'latin1',
			'collation' => 'latin1_swedish_ci',
			'prefix' => '',
		]
	
	],
]);

$container = $app->getContainer();


$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();

$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

$container['view'] = function ($container){
	$view = new \Slim\Views\Twig('../resources/views', [
		'cache' => false,
	]);

	$view->addExtension(new Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));

	$view->getEnvironment()->addGlobal('auth', [

		'check' => $container->auth->check(),
		'user' => $container->auth->user(),

	]);


	$view->getEnvironment()->addGlobal('Students', [

		'usr' => $container->auth->students(),
	]);


	$view->getEnvironment()->addGlobal('flash', $container->flash);

	$view->getEnvironment()->addGlobal('master', $container->workshop->Master());

	return $view;
};

$container['auth'] = function($container) {
	return new \App\Auth\Auth;
};

$container['workshop'] = function($container) {
	return new \App\workshops\Cards;
};


$container['flash'] = function ($container) {
	return new \Slim\Flash\Messages;
};


$container['validator'] = function ($container) {

	return new App\Validation\Validator;

};


/*------------------Controllers------------------*/
$container['HomeController'] = function($container){

	return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container){

	return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container){

	return new \App\Controllers\Auth\PasswordController($container);
};

$container['DashController'] = function($container){

	return new \App\Controllers\DashController($container);
};

$container['csrf'] = function($container) {
	return new \Slim\Csrf\Guard;
};

/*Middlewares*/
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));

$app->add(new \App\Middleware\OldInputsMiddleware($container));

$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');

require_once '../app/routes.php';
