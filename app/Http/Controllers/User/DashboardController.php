<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user->load('badges');

        $allBadges = Badge::all();

        // Format badges for the frontend
        $badges = $allBadges->map(function ($badge) use ($user) {
            $unlocked = $user->badges->contains('id', $badge->id);
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'tier' => $badge->tier,
                'game' => $badge->game,
                'unlocked' => $unlocked,
                'unlocked_at' => $unlocked ? $user->badges->firstWhere('id', $badge->id)->pivot->unlocked_at : null,
            ];
        });

        return inertia('User/Dashboard', [
            'badges' => $badges,
            'stats' => [
                // Mock stats or real stats
                'total_badges' => $user->badges->count(),
                'max_badges' => $allBadges->count(),
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Senha atualizada com sucesso!');
    }
}
