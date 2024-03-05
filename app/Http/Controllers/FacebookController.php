<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function facebookpage()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookredirect()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::updateOrCreate([
                    'email' => $user->email,
                    'name' => $user->name,
                    'facebook_id' => $user->id,
                    'password' => encrypt('12345678dummy')
                ]);
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
}
