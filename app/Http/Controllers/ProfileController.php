<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = auth()->user();

        // Determine base layout based on user role
        $layout = match ($user->role) {
            'admin' => 'layouts.admin',
            'farmer' => 'layouts.farmer',
            default => 'layouts.app',
        };

        return view('profile.edit', compact('user', 'layout'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($user->isFarmer()) {
            $rules['farm_name'] = ['required', 'string', 'max:255'];
            $rules['farm_size'] = ['nullable', 'string', 'max:255'];
            $rules['primary_produce'] = ['nullable', 'string', 'max:255'];
            $rules['bio'] = ['nullable', 'string'];
        } elseif ($user->isConsumer()) {
            $rules['delivery_address'] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($user->isFarmer()) {
            $user->farmerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'farm_name' => $validated['farm_name'],
                    'farm_size' => $validated['farm_size'] ?? null,
                    'primary_produce' => $validated['primary_produce'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                ]
            );
        } elseif ($user->isConsumer()) {
            $user->consumerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'delivery_address' => $validated['delivery_address'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
