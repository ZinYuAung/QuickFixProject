<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCustomerProfileRequest;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return view('customer.profile');
        //user info wiht cus profile,servicearea,city
        $user = Auth::user()->load('customerProfile.serviceArea.city');
        return view('customer.profile', compact('user'));
    }

    /**
     * Show the form for creating a n
     ew resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
        $user = Auth::user()->load('customerProfile');
        return view('customer.profile-edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerProfileRequest $request)
    {
        $user = Auth::user();

        $oldProfileImage = $user->profile_image;

        $newProfileImage = null;

        /*
         * Upload new profile image
         */
        if ($request->hasFile('profile_image')) {

            $newProfileImage = $request->file('profile_image')
                ->store('profile-images', 'public');
        }

        DB::transaction(function () use ($user, $request, $newProfileImage) {

            // Update fields belonging to users table
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,

                'profile_image' => $newProfileImage
                    ?? $user->profile_image,
            ]);

            // Update customer profile
            $user->customerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $request->phone,
                    'city' => $request->city,
                    'address' => $request->address,
                ]
            );
        });

        /*
         * Delete old profile image after
         * the database update succeeds.
         */
        if (
            $newProfileImage &&
            $oldProfileImage &&
            Storage::disk('public')->exists($oldProfileImage)
        ) {
            Storage::disk('public')->delete($oldProfileImage);
        }

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Change the customer's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Check whether the current password is correct.
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        // Save the new password securely.
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Password changed successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
