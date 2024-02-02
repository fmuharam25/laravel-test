<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request)
    {

        try {
            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            $user = User::create($data);
            $result['data'] = $user;
            $result['message'] = 'Register Success';
            $status = 201;
        } catch (Exception $e) {
            $result['data'] = '';
            $result['message'] = $e->getMessage();
            $status = 422;
        }

        return response()->json($result, $status);
    }
}
