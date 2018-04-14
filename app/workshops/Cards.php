<?php

namespace App\workshops;

use App\Models\Student;

use App\Models\Workshop;

use App\Models\Master;

class Cards
{
	public function getWorkshops($Reqpage)
	{
		$perPage = 10;
		$limit =  $Reqpage*$perPage;
		return Workshop::take(10)->skip($limit)->get();
	}

	public function Enroll($wid)
	{
		$wsp = Workshop::get();
		$master = Master::get();
		foreach($wsp as $ws)
		{
			foreach ($master as $mas) 
			{
				if($mas->sid == $_SESSION['user'] && $wid == $mas->wid)
				{
					return false;
				}
			}
		}


		Master::create([
			'wid' => $wid,
			'sid' => $_SESSION['user'],
		]);

		return true;
	}

	public function master()
	{
		return Master::get();
	}

	public function enrolledRecords()
	{
		$wsp = Workshop::get();
		$master = Master::get();
		foreach($wsp as $ws)
		{
			foreach ($master as $mas) 
			{
				if($mas->sid == $_SESSION['user'] && $ws->wid == $mas->wid)
				{
					$wids[] = $ws->wid;
				}
			}
		}
		return $wids;
	}
	public function getUserWorkshops()
	{
		return Workshop::get();
	}
}