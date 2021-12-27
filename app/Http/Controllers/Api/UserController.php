<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    // REGISTER API
    public function register(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        // create data
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        // send response
        return response()->json([
            'message' => "User register successfully",
        ]);
    }

    // LOGIN API [POST]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check user
        $user = User::where('email','=',$request->email)->first();

        if(isset($user->id))
        {
            if(Hash::check($request->password, $user->password))
            {
                // create a token
                $token = $user->createToken('auth_token')->plainTextToken;

                // send a response
                return response()->json([
                    'status' => 1,
                    'message'=> 'User logged in successfully',
                    'access_token' => $token,
                ],200);
            }

        }
        else
        {
            return response()->json([
                'status' => 0,
                'message' => 'User not found !!!'
            ], 404 );
        }
    }

    // LOGIN API WITH SOCIAL PROFILE
    public function redirectSocialLogin()
    {

        $url = Socialite::driver('github')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'status' => 1,
            'message'=> 'Url generated successfully',
            'authorization_url' => $url,
        ]);
    }

    public function callbackSocialLogin(Request $request)
    {

        try {

            $gitUser = Socialite::driver('github')->stateless()->user();
            $isUser = User::where('email', $gitUser->email)->first();

            if($isUser){

                $token = $isUser->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => 1,
                    'message'=> 'User logged in successfully',
                    'access_token' => $token,
                ]);

            }else{
                $user = new User();

                $user->name = $gitUser->name;
                $user->email = $gitUser->email;
                $user->password = Hash::make(rand(100000,999999));

                $user->save();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 1,
                    'message'=> 'User created successfully',
                    'access_token' => $token,
                ]);
            }


        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }


    // CURRENT USER PROFILE
    public function profile()
    {
        return response()->json([
            auth()->user(),
        ]);
    }

    //LOGOUT API
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status'=>1,
            'message'=> 'User logout successfully'
        ]);
    }
}
