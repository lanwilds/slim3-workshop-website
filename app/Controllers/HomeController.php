<?php 

namespace App\Controllers;

use \Slim\Views\Twig as View;

use App\Models\Workshop;

class HomeController extends Controller
{
	public function index($request, $response)
	{
		$works = $this->workshop->getWorkshops(0);
		$totalRecords = Workshop::count();
		$pages = ceil($totalRecords/10);
		$wids = $this->workshop->enrolledRecords();
		return $this->view->render($response, 'app.twig', ['workshop' => $works, 'pages' => $pages, 'r' => 1, 'wids' => $wids]);
	}

	public function Paginate($request, $response)
	{
		$Reqpage = $request->getAttribute('page');
		$Reqpage = $Reqpage-1;
		$totalRecords = Workshop::count();
		$pages = ceil($totalRecords/10);
		$works = $this->workshop->getWorkshops($Reqpage);	
		$wids = $this->workshop->enrolledRecords();	
		return $this->view->render($response, 'app.twig', ['workshop' => $works, 'pages' => $pages, 'r' => $request->getAttribute('page'), 'wids' => $wids]);
	}

	public function EnrollStudent($request, $response)
	{
		$wid =  $request->getAttribute('workshop');
		if($this->workshop->Enroll($wid))
		{
			$this->flash->addMessage('success', 'You have been Enrolled Successful');
		}
		else
		{
			$this->flash->addMessage('error', 'Failed to enroll: You have Already Enrolled');
		}
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function MyWorkshops($request, $response)
	{
		$wids = $this->workshop->enrolledRecords();
		$works = $this->workshop->getUserWorkshops();	
		return $this->view->render($response, 'profile.twig',['works' => $works, 'wids' => $wids]);
	}
}
/*
SELECT->
$user = User::where('email', 'lanwil@coderscoffe.com')->first();

		var_dump($user->email);
		die();
INSERT->
	User::create([
			'username' => 'Lanwil DS',
			'email' => 'lanwildsouza98@gmail.com',
			'password' => '123',
		]);


*/