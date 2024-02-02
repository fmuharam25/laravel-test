<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('username', 'password');

        if (
            Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']]) ||
            Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])
        ) {
            $user = Auth::user();
            $result['data'] = $user;
            $result['message'] = 'Authorized';
            $status = 200;
        } else {
            $result['data'] = '';
            $result['message'] = 'Unauthorized';
            $status = 401;
        }

        return response()->json($result, $status);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ],200);
    }
}
