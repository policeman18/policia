<?php

namespace App\Http\Controllers;

use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Auth;

use Carbon\Carbon;

class AuthController extends Controller
{    


    /**
     * @var SteamAuth
     */
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->middleware('guest');
        $this->steam = $steam;
    }

    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();
                if (is_null($user)) {
                	return redirect('/')->with('status', 'No estás dado de alta como policía.');
                }
                if($user->isDisabled()) {
                    return redirect('/')->with('status', 'Tu cuenta está desactivada.');
                }
                $user->active_at = Carbon::now();
                $user->save();
                Auth::login($user, true);
                return redirect('/home'); // redirect to site
            }
        }
        return $this->steam->redirect(); // redirect to Steam login page
    }
}