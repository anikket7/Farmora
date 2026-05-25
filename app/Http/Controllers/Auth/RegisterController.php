<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ConsumerProfile;
use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showForm(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|digits_between:10,15',
            'role' => 'required|in:farmer,consumer',
            'location' => 'required|string|max:255',
            'farm_name' => 'required_if:role,farmer|nullable|string|max:200',
            'farm_size' => 'nullable|string|max:100',
            'primary_produce' => 'nullable|string|max:200',
            'govt_id' => 'required_if:role,farmer|nullable|file|mimes:jpg,png,pdf|max:20480',
        ], [
            'name.required' => 'Full name is required (min 3 characters).',
            'email.required' => 'A valid, unique email address is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password must be at least 8 characters and match confirmation.',
            'password.confirmed' => 'Passwords do not match.',
            'phone.required' => 'Please enter a valid phone number.',
            'role.required' => 'Please select a valid role.',
            'location.required' => 'Location is required.',
            'farm_name.required_if' => 'Farm name is required for farmer registration.',
            'govt_id.required_if' => 'Government ID upload is required for farmers (JPG/PNG/PDF, max 20MB).',
        ]);

        $govtIdPath = null;
        if ($request->hasFile('govt_id')) {
            $govtIdPath = $request->file('govt_id')->store('govt-ids', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->role === 'consumer' ? 'approved' : 'pending',
            'approved_at' => $request->role === 'consumer' ? now() : null,
            'phone' => $request->phone,
            'location' => $request->location,
        ]);

        if ($user->isFarmer()) {
            FarmerProfile::create([
                'user_id' => $user->id,
                'farm_name' => $request->farm_name,
                'farm_size' => $request->farm_size,
                'location' => $request->location,
                'primary_produce' => $request->primary_produce,
                'govt_id_path' => $govtIdPath,
            ]);
        } else {
            ConsumerProfile::create([
                'user_id' => $user->id,
                'delivery_address' => $request->location,
            ]);
        }

        // Log the user in immediately
        Auth::login($user);

        // Dispatch Registered event to trigger verification email
        event(new Registered($user));

        $message = $user->role === 'consumer'
            ? 'Registration successful! Please enter the 6-digit OTP code sent to your email to verify your account.'
            : 'Registration successful! Please verify your account with the OTP sent to your email. Note that your account will also require admin approval.';

        return redirect()->route('verification.notice')
            ->with('success', $message);
    }
}
