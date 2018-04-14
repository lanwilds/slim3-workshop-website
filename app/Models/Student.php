<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	protected $table = 'students';
	protected $primaryKey ='sid';

	protected $fillable = [
		'email',
		'password',
		'firstname',
		'lastname',
		'contact',
		'qualification',
		'city',
		'state',
		'ip_addr',
	];

	public function setPassword($password)
	{
		
		$this->update([

			'password' => password_hash($password, PASSWORD_DEFAULT)

		]);
	}
}