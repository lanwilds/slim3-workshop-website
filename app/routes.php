<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
$app->group('', function(){

	$this->get('/', 'HomeController:index')->setName('home');

	$this->get('/p/{page}', 'HomeController:Paginate')->setName('home.page');

});


$app->group('', function() {

	$this->get('/enroll/{workshop}', 'HomeController:EnrollStudent')->setName('home.enroll');

})->add(new AuthMiddleware($container));

$app->group('', function() {
	$this->get('/account/signout', 'AuthController:getLogOut')->setName('auth.logout');
	
	$this->get('/account/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');

	$this->get('/account/workshops', 'HomeController:MyWorkshops')->setName('home.profile');

})->add(new AuthMiddleware($container));

$app->group('',function(){

	$this->post('/account/login', 'AuthController:postLogIn')->setName('auth.login');

	$this->get('/account/signup', 'AuthController:getSignUp')->setName('auth.signup');

	$this->post('/account/signup', 'AuthController:PostSignUp')->setName('auth.post.signup');

})->add(new GuestMiddleware($container));
