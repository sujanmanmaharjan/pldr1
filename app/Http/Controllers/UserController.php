<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of system users with search and filters.
     */
    public function index(Request $request)
    {
        $query = User::with('branch');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $branches = Branch::orderBy('name')->get();

        // Statistics
        $totalUsers = User::count();
        $centralCount = User::where('role', 'central')->count();
        $branchCount = User::where('role', 'branch')->count();

        return view('users.index', compact('users', 'branches', 'totalUsers', 'centralCount', 'branchCount'));
    }

    /**
     * Show form to create a new user.
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();

        return view('users.create', compact('branches'));
    }

    /**
     * Store a new user in the database.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // If central role, clear branch_id
        if ($data['role'] === 'central') {
            $data['branch_id'] = null;
        }

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    /**
     * Show form to edit an existing user.
     */
    public function edit(User $user)
    {
        $branches = Branch::orderBy('name')->get();

        return view('users.edit', compact('user', 'branches'));
    }

    /**
     * Update user details and role.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // If central role, clear branch_id
        if ($data['role'] === 'central') {
            $data['branch_id'] = null;
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User details and role permissions updated successfully.');
    }

    /**
     * Delete user from the system.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own active administrator account.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User account deleted successfully.');
    }
}

