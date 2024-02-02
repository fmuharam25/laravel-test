<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Validator;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request)
    {

        try {
            $user = User::create($request->all());
            $result['data'] = $user;
            $result['message'] = 'Register Success';
            $status = 201;
        } catch (Exception $e) {
            $result['data'] = '';
            $result['message'] = $e->getMessage();
            $status = $e->getCode() == 0 ? 200 : $e->getCode();
        }

        return response()->json($result, $status);
    }
}
