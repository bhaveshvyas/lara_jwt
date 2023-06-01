<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    /**
     * class constructor
     *
     * @author BV
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * login user
     *
     * @param Request $request
     * @return void
     * @author BV
     */
    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ];

        $input     = $request->only('name', 'email', 'password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->messages(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status'        => 'success',
            'user'          => $user,
            'authorisation' => [
                'token' => $token,
                'type'  => 'bearer',
            ],
        ]);

    }

    /**
     * register user
     *
     * @param Request $request
     * @return void
     * @author BV
     */
    public function register(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];

        $input     = $request->only('name', 'email', 'password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->messages(),
            ], 422);
        }

        $user           = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        $token = Auth::login($user);

        return response()->json([
            'status'        => 'success',
            'message'       => 'User created successfully',
            'user'          => $user,
            'authorisation' => [
                'token' => $token,
                'type'  => 'bearer',
            ],
        ]);
    }

    /**
     * logout user
     *
     * @return void
     * @author BV
     */
    public function logout()
    {
        // $AuthToken = $_SERVER['HTTP_AUTHORIZATION']; To access the header token
        Auth::logout();
        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * refresh token
     *
     * @return void
     * @author BV
     */
    public function refresh()
    {
        return response()->json([
            'status'        => 'success',
            'user'          => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type'  => 'bearer',
            ],
        ]);
    }

    /**
     * get user
     *
     * @return void
     * @author BV
     */
    public function getUser()
    {
        $users = User::where('id', Auth::id())->first();
        dd($users);
    }
}
