<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignupRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function index()
    {
        return view('auth.signup');
    }
    public function store(
        AuthService $authService,
        StoreSignupRequest $request
    ) {
        $fields = $request->validated();
        if ($authService->signup($fields)) {
            return response()->json([
                'message' => 'Signup Successfully',
                'data' => null,
            ], 201);
        }
    }
}
