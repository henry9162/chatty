<?php 

namespace Chatty\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	protected $table = 'statuses';

	protected $fillable = [
		'body'
	];

	public function user()
	{	//A relationship is needed to relate the status back to the user as well incase for a specific status where we would need
		// to access users information which we will be doing

		return $this->belongsTo('Chatty\Models\User', 'user_id');//the status belongs to users with user_id of the status table equals the id of the users table
	}

	public function scopeNotReply($query)
	{	
		//Since the replies will be on same table with the statuses, we need to specify a scope to distinguish thos
		//Since the parent_id is what we would be using to tie to our statuses o make it a reply,
		//We set to null to filter out which and which we dont want.
		//Remember, the parent_id is nullable by default from migration.
		return $query->whereNull('parent_id');
	}

	public function replies()
	{
		return $this->hasMany('Chatty\Models\Status', 'parent_id');//the status has many replies with parent_id of the status table equals the id of the users table
	}

	public function likes()
	{
		//We need to be able to grab who, or what has liked the status
		//likeable is the name of a method 
		return $this->morphMany('Chatty\Models\Like', 'likeable');
	}
}

?>