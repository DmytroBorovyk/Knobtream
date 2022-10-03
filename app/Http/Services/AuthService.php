<?php

namespace App\Http\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(LoginRequest $request): string
    {
        if ( ! Auth::attempt($request->validated())) {
            return response()->json([
                'message' => 'Bad credentials',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return $user->createToken("API TOKEN")->plainTextToken;
    }

    public function registration(RegistrationRequest $request): string
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'balance' => Setting::where('setting', 'default_coins')->first()->value,
        ]);

        return $user->createToken("API TOKEN")->plainTextToken;
    }
}
