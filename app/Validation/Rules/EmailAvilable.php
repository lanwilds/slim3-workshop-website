<?php


namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

use App\Models\Student;

class EmailAvilable extends AbstractRule
{

	public function validate($input)
	{
		$email = Student::where('email', $input)->count();
		if($email == false){ return true; }
		$email = Student::where('email', $input)->where('sid', $_SESSION['user'])->get();
		foreach($email as $mail) 
		{
			if( $mail->email == $input)
			{
				return true; 
			}
		}
		return false;
	}

}