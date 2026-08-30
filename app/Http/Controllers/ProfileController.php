<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.index', compact('user'));
    }
    public function update(
        UpdateProfileRequest $request,
        AuthService $authService,
    ) {
        $user = Auth::user();
        $fields = $request->validated();
        $authService->update($user, $fields);
        return response()->json([
            'message' => 'Saved',
            'data' => [],
        ]);
    }
}
