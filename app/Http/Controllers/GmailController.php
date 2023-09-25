<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GmailController extends Controller
{
    
 public function googleRedirect()
 {
     return Socialite::driver('google')->redirect();
 }
 
 public function googleCallback()
 {
 
     $user_google = Socialite::driver('google')->user();
    
     $user =User::updateOrCreate([
         'google_id'=> $user_google->id,
     ],[
         'name'=> $user_google->name,
         'email'=> $user_google->email,
         
     ]);
 
     Auth::login($user);
     return redirect('/dashboard');
 }
}
