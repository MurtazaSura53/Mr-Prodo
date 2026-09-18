<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function store(
        StoreLoginRequest $request,
    ) {
        $fields = $fields = $request->validated();

        if (Auth::attempt($fields)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => "Login Successfully",
                'data' => null,
            ], 201);
        }
        return response()->json([
            'message' => "Unauthorized",
            'errors' => [
                'password' => 'Invalid Email or Password'
            ],
        ], 401);
    }
    public function destroy(AuthService $authService)
    {
        $authService->logout();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
