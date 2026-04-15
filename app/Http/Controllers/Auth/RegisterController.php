<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return inertia('Auth/Register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'accepted_at' => now(),
            'accepted_terms_version' => config('app.terms_version', '1.0'),
        ]);

        if (Auth::attempt(['email' => $user['email'], 'password' => $request->password])) {
            return redirect()->route('dashboard_index')->with('success', 'Usuario criado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Usuario não foi criado!');
        }

    }
}
