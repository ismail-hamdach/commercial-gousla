<?php
use Illuminate\Support\Facades\Session;
use App\Models\User; // assuming you have a User model

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/location',function(Request $request) {
    \Log::info($request);
    // session(['location'=>['lant' =>$request->latitude , "lng"=>$request->longitude]]);
        $userId  =  $request->userId ;  
        $user = User::find($userId); // retrieve the user instance
        Session::put("user.{$user->id}.data", ['location'=>['lant' =>$request->latitude , "lng"=>$request->longitude]]); // store data in the session
       
            \Log::info(auth()->user());
         
});