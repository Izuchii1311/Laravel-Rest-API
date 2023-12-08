<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function Login(Request $request) {
        // Validasi Inputan
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah email sama dengan email di inputan
        $user = User::where('email', $request->email)->first();

        // Cek apakah password sama dengan inputan password
        if(!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create Token jika berhasil Login
        return $user->createToken($user->username)->plainTextToken;
    }

    public function me (Request $request) {
        $user = Auth::user();
        // $post = Post::where('user', $post->id);

        return response()->json(Auth::user());
    }

    public function Logout(Request $request) {
        // Revoke all tokens...
        $request->user()->tokens()->delete();
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
