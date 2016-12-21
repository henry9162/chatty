<?php 

namespace Chatty\Http\Controllers;

use Auth;
use Chatty\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller{

	public function getIndex()
	{

		$friends=Auth::user()->friends();
		$requests=Auth::user()->friendRequests();


		return view('friends.index')
		->with('friends', $friends)
		->with('requests', $requests);
	}

	public function getAdd($username)
	{
		$user = User::where('username', $username)->first();

		if (!$user){
			return redirect()->route('home')->with('info', 'that user could not be found');
		}


		if(Auth::user()->id === $user->id){
			return redirect()->route('home');
		}


		if (Auth::user()->hasFriendRequestsPending($user) || $user->hasFriendRequestsPending(Auth::user())){
			return redirect()->route('profile.index', ['username' => $user->username])
			->with('info', 'Friend request already pending.');
		}

		if (Auth::user()->isFriendWith($user)){
			return redirect()->route('profile.index', ['username' => $user->username])
				->with('info', 'You are already friends.');
		}

		Auth::user()->addFriend($user);
		return redirect()->route('profile.index', ['username' =>$user->username])->with('info', 'Friend request sent.');
	}

	public function getAccept($username)
	{
		$user = User::where('username', $username)->first();

		if (!$user){
			return redirect()->route('home')->with('info', 'that user could not be found');
		}

		if (!Auth::user()->hasFriendRequestsRecieved($user)){
			return redirect()->route('home');
		}

		Auth::user()->acceptFriendRequest($user);

		return redirect()->route('profile.index', ['username' => $user->username])->with('info', 'Friend request accepted.');

	}

	public function postDelete($username)
	{
		$user = User::where('username', $username)->first();

		if (!Auth::user()->isFriendWith($user)){
			return redirect()->back();
		}

		Auth::user()->deleteFriend($user);

		return redirect()->back()->with('info', 'friend deleted.');

	}

}

?>