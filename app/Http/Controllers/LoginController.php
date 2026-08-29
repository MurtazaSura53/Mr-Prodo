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

            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'password' => 'Invalid email or password.',
        ]);
    }
}
