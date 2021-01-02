<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function signUp(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()
                ->json($validator->errors())
                ->setStatusCode(400);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $newUser = new User();
        $newUser->email = $email;
        $newUser->password = password_hash($password, PASSWORD_BCRYPT);
        $newUser->save();

        $response = [
            "id" => $newUser['id'],
            "email" => $newUser['email'],
            "token" => $newUser['token'],
        ];

        return response()->json($response)->setStatusCode(201);
    }

    public function login(Request $request) {

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()
                ->json(['error' => 'wrong e-mail/password'])
                ->setStatusCode(400);
        }

        return response()->json(['token' => $token]);
    }

    public function logout() {
        Auth::logout();
    }

    public function me() {
        $user = Auth::user();

        return $user;
    }
}
