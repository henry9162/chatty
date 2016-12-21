<?php 

namespace Chatty\Http\Controllers;

use Auth;
use Chatty\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller{

	public function getProfile($username)
	{
		$user = User::where('username', $username)->first();

		if (!$user){
			abort(404);
		}

		//Below is the relationship that gives us all our statuses
		//We include notReply, cos we just want our own statuses, and not what we reply to
		$statuses = $user->statuses()->notReply()->get();
		
		return view('profile.index')
		->with('user', $user)
		->with('statuses', $statuses)
		->with('authUserIsFriend', Auth::user()->isFriendWith($user)); 

	}

	public function getEdit()
	{
		return view('profile.edit');
	}

	public function postEdit(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'alpha|max:50',
			'last_name' => 'alpha|max:50',
			'location' => 'max:20',
		]);

		Auth::user()->update([
			'first_name' => $request->input('first_name'),
			'last_name' => $request->input('last_name'),
			'location' => $request->input('location'),
		]);

		// I'm thinking you can call user as class with capital letters and as a method with small letters user()
		return redirect()->route('profile.edit')->with('info', 'Your proile has been updated');
	}
}

?>