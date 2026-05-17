<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['farmerProfile', 'consumerProfile', 'products', 'orders']);

        return view('admin.users.show', compact('user'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', "{$user->name}'s account has been approved.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $user->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', "{$user->name}'s account has been rejected.");
    }

    public function suspend(User $user): RedirectResponse
    {
        $user->update([
            'status' => 'suspended',
        ]);

        return back()->with('success', "{$user->name}'s account has been suspended.");
    }
}
