<?php 

namespace Chatty\Http\Controllers;

use Auth;
use Chatty\Models\Status;

class HomeController extends Controller{

	public function index(){

		if(Auth::check()){ //Normally, we would return all statuses where the user_id matches our user id, or where the user_id record
			//is in our friends lists
			$statuses = Status::notReply()->where(function($query){
				return $query->where('user_id', Auth::user()->id)
				//We need each of our friends id, so that why we use the list method
				->orWhereIn('user_id', Auth::user()->friends()->lists('id'));

			})
			->orderBy('created_at', 'desc')
			->paginate(10);


			return view('timeline.index')->with('statuses', $statuses);
		}

		return view('home');
	}
}

?>