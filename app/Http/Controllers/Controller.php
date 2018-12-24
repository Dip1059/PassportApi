<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function signup(Request $req)
    {
    	$user=User::create(
    		[
    			'name'=>$req->name,
    			'email'=>$req->email,
    			'password'=>$req->password
    		]
    	);

    	if($user){
    		/*$http = new Client();
			$response = $http->post('http://localhost:8000/oauth/token', [
    		'form_params' => [
        	'grant_type' => 'password',
        	'client_id' => 2,
        	'client_secret' => 'zKN0skJCn1BLZuZW2g3LdxSGwQC4EyYEKd4rU6gE',
        	'username' => $user->email,
        	'password' => $user->password,
        	'scope' => ''
    		],
			]);*/

			$token=$user->createToken($user->email)->accessToken;
			$response=[
				'success' => true,
                'status_code' => 200,
                'message' => __("Successfully Signed up!"),
                'data' => [
                    'access_token' => $token,
                    'access_type' =>"Bearer"
                ]
			];
			return response()->json(['data'=>$response]);
    	}
    	return response()->json(['msg'=>'Try again']);
    	
    }

    public function login(Request $req)
    {
    	$user=User::where([['email','=',$req->email],['password','=',$req->password]])->first();
    	if($user){
    		$token=$user->createToken($user->email)->accessToken;
			$response=[
				'success' => true,
                'status_code' => 200,
                'message' => __("Successfully logged in!"),
                'data' => [
                    'access_token' => $token,
                    'access_type' =>"Bearer"
                ]
			];
			return response()->json(['data'=>$response,'user'=>$user]);
    	}
    	return response()->json(['msg'=>'Try again.']);

    }

    public function allUsers()
    {
    	$user=User::all();
    	return response()->json(['data'=>$user]);
    }


}

