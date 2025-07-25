<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SaasSellerProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class SellerRegistrationController extends Controller
{
    /**
     * Display the seller registration view.
     */
    public function create(): View
    {
        return view('auth.seller-register');
    }

    /**
     * Handle an incoming seller registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'store_name' => ['required', 'string', 'max:255'],
            'store_description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'terms' => ['required', 'accepted'],
        ]);

        DB::beginTransaction();

        try {
            // Create the seller user
            $seller = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'seller',
                'is_active' => true,
            ]);

            // Create seller profile
            SaasSellerProfile::create([
                'user_id' => $seller->id,
                'store_name' => $request->store_name,
                'store_description' => $request->store_description,
                'address' => $request->address,
                'is_approved' => false, // Sellers need admin approval
            ]);

            event(new Registered($seller));

            Auth::login($seller);

            DB::commit();

            return redirect()->route('seller.dashboard')
                ->with('success', 'Your seller account has been created successfully! Please wait for admin approval to start selling.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while creating your account. Please try again.']);
        }
    }
}
