<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(RegisterRequest $request)
    {
        if ($userId = User::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ])) {
            return response()->json([
                'id' => $userId,
            ])->setStatusCode(201, 'Created');
        }
        return response()->json([

        ])->setStatusCode(422, 'Unprocessable entity');
    }

    public function login(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validateData->fails()) {
            return $validateData->messages();
        }

        if ($user = User::query()->where(['phone' => $request->phone,])->first()
            and
            Hash::check($request->password, $user->password)
        ) {
            $user->api_token = Str::random(40);
            $user->save();

            return response()->json([
                'auth_token' => $user->api_token,
            ])->setStatusCode(200, 'Ok');
        }

        return response()->json(['login' => 'Incorrect login or password'])->setStatusCode(422, 'Unprocessable entity');
    }

    public function logout()
    {
        Auth::user()->logout();

        return response()->json([
            'message' => 'logged out',
        ])->setStatusCode(200, 'Ok');
    }
}
