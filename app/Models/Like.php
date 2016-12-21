<?php 

namespace Chatty\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $table = 'likeable';

	public function likeable()
	{
		//This says that i am a polymorphic relationship, and i can be applied to any other model.
		return $this->morphTo();
	}

	public function user()
	{
		//We want a relationship to see who liked something, so later on, we can output a list of people who liked something. 
		return $this->belongsTo('Chatty\Models\User', 'user_id');
	}


}


?>