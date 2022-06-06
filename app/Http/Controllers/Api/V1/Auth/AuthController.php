<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Auth;

use ClassyPOS\Http\Controllers\Controller;
use ClassyPOS\Models\Users\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller {

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth:api', ['except' => ['login']]);
//    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password'); // grab credentials from the request
        try {
            if (!$token = JWTAuth::attempt($credentials)) { // attempt to verify the credentials and create a token for the user
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500); // something went wrong whilst attempting to encode the token
        }

//        return response()->json(['token' => "Bearer $token"]);
        return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
            ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'passwordConfirmation' => 'required|string|min:6',
            ]
        );

        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $token = JWTAuth::attempt($request->only('email', 'password'));

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }

}