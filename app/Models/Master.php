<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
	protected $table = 'student-workshop';
	protected $primaryKey ='id';

	protected $fillable = [
		'sid',
		'wid',
	];
}