<?php 

namespace Chatty\Http\Controllers;

use Auth;
use Chatty\Models\User;
use Chatty\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller{

	public function postStatus(Request $request)
	{	//THIS HANDLES VALIDATION AND POSTING OF STATUS

		$this->validate($request, [
			'status' => 'required|max:1000',
		]);

		//Here is our relationship method, automatically synchronising the statuses of a particular user where user_id = id
		//user_id is automatically detect the id of the user posting the status, then inserts it in the status table

		Auth::user()->statuses()->create([
			'body' => $request->input('status'),
		]);

		return redirect()->route('home')->with('info', 'Status posted.');
	}


	public function postReply(Request $request, $statusId)
	{
		$this->validate($request, [
			"reply-{$statusId}" => 'required|max:1000'
		], [
			'required' => 'The reply body is required.'
		]);

		//Finds the status that we need to reply to
		// So this $status is just a number signifying the status id
		$status = Status::notReply()->find($statusId);


		//Checks if that status id exist before going further.
		if (!$status){
			return redirect()->route('home');
		}

		//$status->user, means the user who owns that status, remember we have a relationship between status and user
		// Auth::user()->id !== $status->user->id, what this means is that it allows us to reply to our own status
		if (!Auth::user()->isFriendWith($status->user) && Auth::user()->id !== $status->user->id){
			return redirect()->route('home');
		}

		//All what user()->associate(Auth::user()) means is that, we associate ourelves with the user relation in the status model
		//This simply means that when we create the status we have the user_id set to whatever
		// Then we save the $reply to the replies relation in the status model
		// This will create our parent_id automatically from that.
		//The parent_id relate directly with the id the same status table
		$reply = Status::create([
			'body' => $request->input("reply-{$statusId}"),])->user()->associate(Auth::user());

		$status->replies()->save($reply);

		return redirect()->back();
	}

	public function getLike($statusId)
	{
		$status = Status::find($statusId);

		if (!$status){
			return redirect()->route('home');
		}

		if (!Auth::user()->isFriendWith($status->user)){
			return redirect()->route('home');
		}

		if (Auth::user()->hasLikedStatus($status)){
			return redirect()->back();
		}

		$like = $status->likes()->create([]);
		Auth::user()->likes()->save($like);

		return redirect()->back();
	}

}

?>