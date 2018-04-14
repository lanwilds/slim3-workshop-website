<?php 

namespace App\Controllers\Auth;

use App\Models\Student;

use App\Controllers\Controller;

use Respect\Validation\Validator as v;

class AuthController extends Controller
{
	public function getLogOut($request, $response)
	{
		$this->auth->logout();
		$this->flash->addMessage('success', 'You have been Logged Out');

		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getSignIn($request, $response)
	{
		return $this->view->render($response, 'auth/signin.twig');
	}


	public function postLogIn($request, $response)
	{
		$auth = $this->auth->attempt(
			$request->getParam('email'),

			$request->getParam('password')

		);

		if(!$auth)
		{
			$this->flash->addMessage('error', 'Could Not Login, Invalid Credentials');

			return $response->withRedirect($this->router->pathFor('home'));
		}

		$this->flash->addMessage('info', 'You have been Logged In!');
		return $response->withRedirect($this->router->pathFor('home'));

	}

	public function getSignUp($request, $response)
	{
		return $this->view->render($response, 'auth/signup.twig');
	}


	public function postSignUp($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->emailAvilable(),
			
			'firstname' => v::notEmpty()->alpha()->length(5, 15),

			'lastname' => v::notEmpty()->alpha()->length(5, 15),

			'city' => v::notEmpty()->alpha()->length(5, 15),

			'state' => v::notEmpty()->alpha()->length(5, 15),

			'contact' => v::notEmpty()->numeric()->length(10, 10),

			'qualification' => v::notEmpty(),

			'repassword' => v::notEmpty()->length(8, 50),
			
			'password' => v::notEmpty()->length(8, 50),


		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		if($request->getParam('password') != $request->getParam('repassword'))
		{
			$this->flash->addMessage('error', 'Password Mismatch!');
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$user = Student::create([
			'email' => $request->getParam('email'),
			'firstname' => $request->getParam('firstname'),
			'lastname' => $request->getParam('lastname'),
			'city' => $request->getParam('city'),
			'state' => $request->getParam('state'),
			'contact' => $request->getParam('contact'),
			'qualification' => $request->getParam('qualification'),
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
		]);

		$auth = $this->auth->attempt(

			$request->getParam('email'),

			$request->getParam('password')

		);

		$this->flash->addMessage('info', 'Signin successful!');

		return $response->withRedirect($this->router->pathFor('home'));

	}
}
