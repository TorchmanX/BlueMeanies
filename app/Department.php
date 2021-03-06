<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'Department';

	public $timestamps = false;

	public function toObject(){
		return (object) [
			'id' => $this->ID,
			'name' => $this->Name
		];
	}

}