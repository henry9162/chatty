<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
|You should have a rout for every method... or rather mostly every method
*/

/*
*Home
*/

Route::get('/', [
'uses' => '\Chatty\Http\Controllers\HomeController@index', 
'as' => 'home',// 'home' here signifies the HomeController class with method index, which returns a view of home, i.e home.blade.php#
//'middleware' => ['guest'],
	]);

/*
*alert
*/

Route::get('/alert', function(){//The alert here signifies a page that will be inuted as alert, in otherwords, its the name of a page

	return redirect()->route('home')->with('info', 'You have signed up!');
});

/*
*Authentication
*/

Route::get('/signup', [
'uses' => '\Chatty\Http\Controllers\AuthController@getSignup', 
'as' => 'auth.signup',// 'auth.signup' this is generally used as a kind of namespace...signup alone should work too.
'middleware' => ['guest'],
	]);

Route::post('/signup', [
'uses' => '\Chatty\Http\Controllers\AuthController@postSignup', 
'middleware' => ['guest'],
	]);


Route::get('/signin', [
'uses' => '\Chatty\Http\Controllers\AuthController@getSignin', 
'as' => 'auth.signin',
'middleware' => ['guest'],
	]);

Route::post('/signin', [
'uses' => '\Chatty\Http\Controllers\AuthController@postSignin', 
'middleware' => ['guest'],
	]);


Route::get('/signout', [
'uses' => '\Chatty\Http\Controllers\AuthController@getSignout', 
'as' => 'auth.signout',
	]);

/*
*Search
*/

Route::get('/search', [
'uses' => '\Chatty\Http\Controllers\SearchController@getResults', 
'as' => 'search.results',
	]);


/*
*User profile
*/

Route::get('/user/{username}', [
'uses' => '\Chatty\Http\Controllers\ProfileController@getProfile', 
'as' => 'profile.index',
	]);


Route::get('/profile/edit', [
'uses' => '\Chatty\Http\Controllers\ProfileController@getEdit', 
'as' => 'profile.edit',
'middleware' => ['auth'],
	]);

Route::post('/profile/edit', [
'uses' => '\Chatty\Http\Controllers\ProfileController@postEdit', 
'middleware' => ['auth'],
	]);

/*
*User profile
*/

Route::get('/friends', [
'uses' => '\Chatty\Http\Controllers\FriendController@getIndex', 
'as' => 'friends.index',
'middleware' => ['auth'],
	]);

Route::get('/friends/add/{username}', [
'uses' => '\Chatty\Http\Controllers\FriendController@getAdd', 
'as' => 'friends.add',
'middleware' => ['auth'],
	]);


Route::get('/friends/accept/{username}', [
'uses' => '\Chatty\Http\Controllers\FriendController@getAccept', 
'as' => 'friends.accept',
'middleware' => ['auth'],
	]);

Route::post('/friends/delete/{username}', [
'uses' => '\Chatty\Http\Controllers\FriendController@postDelete', 
'as' => 'friends.delete',
'middleware' => ['auth'],
	]);

/*
*Statuses
*/

Route::post('/status', [
'uses' => '\Chatty\Http\Controllers\StatusController@postStatus', 
'as' => 'status.post',
'middleware' => ['auth'],
	]);

Route::post('/status/{statusId}/reply', [
'uses' => '\Chatty\Http\Controllers\StatusController@postReply', 
'as' => 'status.reply',
'middleware' => ['auth'],
	]);


Route::get('/status/{statusId}/like', [
'uses' => '\Chatty\Http\Controllers\StatusController@getLike', 
'as' => 'status.like',
'middleware' => ['auth'],
	]);