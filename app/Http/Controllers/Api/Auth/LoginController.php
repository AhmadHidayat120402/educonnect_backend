<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required', 'min:8'],

        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response([
                "message" => ['Email not found'],
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['password is wrong'],
            ], 404);
        }
        $userData = $user->toArray();
        $userData['username'] = $userData['username'] ?? $request->username ?? '';

        if (Auth::attempt($credentials)) {
            return response()->json([
                'success' => 'Login Successfully',
                'user' => $userData,
            ]);
        }

        return response()->json([
            'error' => 'Your Credentials not match',
        ]);
    }
}
