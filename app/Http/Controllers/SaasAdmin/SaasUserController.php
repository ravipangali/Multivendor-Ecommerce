<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class SaasUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = $request->role;

        if ($role) {
            $users = User::where('role', $role)->latest()->paginate(15);
        } else {
            $users = User::latest()->paginate(15);
        }

        return view('saas_admin.saas_user.saas_index', compact('users', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin', 'seller', 'customer'];
        return view('saas_admin.saas_user.saas_create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,seller,customer',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->is_active = $request->is_active;
        $user->save();

        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            $filename = 'profile_photos/' . uniqid() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->storeAs('public', $filename);
            $user->profile_photo = $filename;
            $user->save();
        }

        toast('User created successfully', 'success');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Load relationships based on user role
        if ($user->role === 'seller') {
            $user->load('sellerProfile', 'products');
        } elseif ($user->role === 'customer') {
            $user->load('customerProfile', 'customerOrders');
        }

        return view('saas_admin.saas_user.saas_show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'seller', 'customer'];
        return view('saas_admin.saas_user.saas_edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,seller,customer',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->is_active = $request->is_active;

        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $profilePhoto = $request->file('profile_photo');
            $filename = 'profile_photos/' . uniqid() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->storeAs('public', $filename);
            $user->profile_photo = $filename;
        }

        $user->save();

        toast('User updated successfully', 'success');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Delete profile photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Check relationships before deleting
        if ($user->role === 'seller') {
            if ($user->products()->count() > 0 || $user->sellerOrders()->count() > 0) {
                toast('Cannot delete user because they have products or orders', 'error');
                return redirect()->route('admin.users.index');
            }
        } elseif ($user->role === 'customer') {
            if ($user->customerOrders()->count() > 0) {
                toast('Cannot delete user because they have orders', 'error');
                return redirect()->route('admin.users.index');
            }
        }

        $user->delete();

        toast('User deleted successfully', 'success');
        return redirect()->route('admin.users.index');
    }
}
