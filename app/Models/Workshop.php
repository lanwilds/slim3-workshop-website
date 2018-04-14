<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
	protected $table = 'workshop';
	protected $primaryKey ='wid';

	protected $fillable = [
		'title',
		'description',
		'banner',
		'conductor',
		'contact',
		'date',
		'expiry',
	];
}