<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $model = $request->all();
            $model['password'] = Hash::make($model['password']);
            $data = User::create($model);
            if ($data) {
                return response()->json(["message" => "register successfull", "data" => $data]);
            } else {
                return response()->json(["message" => "register failed"]);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => "errors"]);
        }
    }

    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('LaravelMyApp')->accessToken;
                $data = [
                    'token' => $token,
                    'user' => $user,
                ];
                return response()->json($this->getRespon($data, 200, "login success"));
            } else {
                return response()->json($this->getRespon(null, 204, "user not found"));
            }
        } catch (\Exception $e) {
            return response()->json($this->getRespon(null, 500, "errors"));
        }
    }

    public function detail()
    {
        try {
            $data = Auth::user();
            if ($data) {
                return response()->json($this->getRespon($data, 200, "detail user"));
            } else {
                return response()->json($this->getRespon(null, 204, "user not found"));
            }
        } catch (\Exception $e) {
            return response()->json($this->getRespon(null, 500, "error"));
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user()->token();
            $user->revoke();
            return response()->json($this->getRespon(null, 200, "successfully logged out"));
        } catch (\Exception $e) {
            return response()->json($this->getRespon(null, 500, "error"));
        }
    }

    public function getRespon($data = null, $code, $message)
    {
        return ["code" => $code, "message" => $message, "data" => $data];
    }
}
