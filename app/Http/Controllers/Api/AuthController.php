<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;
    public function login(ApiLoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Unauthorized', 401);
        }
        $user = User::firstWhere('email', $request->email);
        return $this->ok('Authenticated', [
            'token' => $user->createToken(
                'API Token for ' . $user->email,
                ['*'],
                now()->addMonth()
            )->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok('Logout');
    }
    public function register()
    {
        return $this->ok('register');
    }
}
