<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\User;

class UserController extends Controller
{    

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users() {
    	// $users = User::where('disabled', 0)->orderBy('rank', 'desc')->get();

        $cnp = User::where('disabled', 0)->where('corp', 1)->orderBy('rank', 'desc')->get();
        $gc = User::where('disabled', 0)->where('corp', 2)->orderBy('rank', 'desc')->get();
        $cadetes = User::where('disabled', 0)->where('corp', 0)->orderBy('rank', 'desc')->get();

        $last = User::orderBy('updated_at', 'desc')->first()->getLastUpdatedDiff();

    	// TODO last updated
    	return view('users.list')->with('cnp', $cnp)->with('gc', $gc)->with('cadetes', $cadetes)->with('last', $last);
    }

    public function profile($id) {
        $user = User::findOrFail($id);
        return view('users.profile')->with('user', $user);
    }

    public function searchInput(Request $request) {
        if(is_null($request->q) || $request->q == "" || strlen($request->q) < 3 ) {
            return [];
        }

        $users = User::where('name', $request->q)
    ->orWhere('name', 'like', '%' . $request->q . '%')->where('disabled', 0)->get();

        $result = [];
        $i = 0;
        foreach ($users as $user) {
            $result[$i] = ['id' => $user->id, 'text' => $user->name, 'corp' => $user->corp, 'rank' => $user->getRankName()];
            $i++;
        }

        return $result;
    }
}
