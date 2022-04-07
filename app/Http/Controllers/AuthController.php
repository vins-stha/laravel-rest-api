<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller
{
    /**
     * AuthController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(
            'auth:api',
            [
                'except' => ['login', 'register']
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:5',
                're-password' => 'required|same:password'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    "validation_errors" => $validator->errors()
                ], 400);
        } else {
            $user = User::create(
                array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->get('password'))]
                )
            );
            return response()->json([
                "message" => "User registered",
                "user" => $user
            ], 201);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    "validattion_errors" => $validator->errors()
                ], 422);
        }
        else {
            if(!$token = auth()->attempt($validator->validated())){
                return response()->json([
                    "message" => "Unauthorized user",
                ], 401);
            }
            return $this->createToken($token);
        }
    }


    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function createToken($token){
        return response()->json([

            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json([
            "message" => "Logout Successful",
        ], 200);
    }

}
