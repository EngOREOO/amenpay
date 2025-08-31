<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'national_id' => 'nullable|string|max:255',
            'language' => 'required|in:en,ar',
            'status' => 'required|in:active,inactive,suspended',
            'is_verified' => 'boolean',
            'email_verified' => 'boolean',
        ]);

        $userData = $request->only(['name', 'phone', 'email', 'national_id', 'language', 'status']);
        $userData['is_verified'] = $request->has('is_verified');
        $userData['email_verified_at'] = $request->has('email_verified') ? now() : null;

        $user = User::create($userData);

        // Create wallet for the user
        $user->wallet()->create([
            'wallet_number' => 'W' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
            'balance' => 0.00,
            'currency' => 'SAR',
            'status' => 'active'
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'national_id' => 'nullable|string|max:255',
            'language' => 'required|in:en,ar',
            'status' => 'required|in:active,inactive,suspended',
            'is_verified' => 'boolean',
            'email_verified' => 'boolean',
        ]);

        $userData = $request->only(['name', 'phone', 'email', 'national_id', 'language', 'status']);
        $userData['is_verified'] = $request->has('is_verified');
        $userData['email_verified_at'] = $request->has('email_verified') ? now() : null;

        $user->update($userData);

        // Update wallet status if provided
        if ($request->has('wallet_status') && $user->wallet) {
            $user->wallet->update(['status' => $request->wallet_status]);
        }

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);
        
        return response()->json(['success' => true]);
    }

    public function verifyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_verified' => true]);
        
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json(['success' => true]);
    }

    public function userTransactions($id)
    {
        $user = User::findOrFail($id);
        $transactions = $user->transactions()->latest()->paginate(20);
        
        return response()->json(['data' => $transactions]);
    }

    public function userWallet($id)
    {
        $user = User::findOrFail($id);
        $wallet = $user->wallet;
        
        return response()->json(['data' => $wallet]);
    }

    public function userCards($id)
    {
        $user = User::findOrFail($id);
        $cards = $user->cards;
        
        return response()->json(['data' => $cards]);
    }

    public function userSessions($id)
    {
        $user = User::findOrFail($id);
        $sessions = $user->sessions;
        
        return response()->json(['data' => $sessions]);
    }

    public function viewTransactions($id)
    {
        $user = User::findOrFail($id);
        $transactions = $user->transactions()->latest()->paginate(20);
        
        return view('admin.users.transactions', compact('user', 'transactions'));
    }

    public function viewCards($id)
    {
        $user = User::findOrFail($id);
        $cards = $user->cards()->latest()->get();
        
        return view('admin.users.cards', compact('user', 'cards'));
    }

    public function suspendUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'suspended']);
        
        return response()->json(['success' => true, 'message' => 'User suspended successfully']);
    }

    public function exportUsers(Request $request)
    {
        // Export users logic
        return response()->json(['message' => 'Export started']);
    }

    public function bulkActions(Request $request)
    {
        // Bulk actions logic
        return response()->json(['message' => 'Bulk actions completed']);
    }
}
