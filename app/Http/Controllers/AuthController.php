<?php 

namespace Chatty\Http\Controllers;

use Auth;
use Chatty\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

	public function getSignup()
	{
		return view('auth.signup');
	}

	public function postSignup(Request $request)
	{
		//dd('sign up');//dd means die & dump!!
		$this->validate($request, [
			//The email must be required, unique to just one user, in the users table, it must be an email, and must have a maximum character of 255 length
			'email' => 'required|unique:users|email|max:255',
			'username' => 'required|unique:users|alpha_dash|max:20',
			'password' => 'required|min:6',
		]);

		//dd('all ok');

		User::create([
			'email' => $request->input('email'),
			'username' => $request->input('username'),
			'password' => bcrypt($request->input('password')),
		]);

		return redirect()->route('home')->with('info', 'Your account has been created and you can now sign in.');
	}


	public function getSignin()
	{
		return view('auth.signin');
	}


	public function postSignin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required',
		]);
		
		if (!Auth::attempt($request->only(['email', 'password']), $request->has('remember'))){
			return redirect()->back()->with('info', 'Could not sign you in with these details, please check and try again.');
		}

		// The only helper function can only pass in maximum of two variables
		return redirect()->route('home')->with('info', 'You are now signed in!.');
	}

	public function getSignout()
	{
		Auth::logout();

		return redirect()->route('home');
	}
}

?>