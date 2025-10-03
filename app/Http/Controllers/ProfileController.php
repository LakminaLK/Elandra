<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user profile page
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your profile.');
        }
        
        return view('profile.show', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'password');
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'], 'password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Password updated successfully!');
    }

    /**
     * Update mobile number
     */
    public function updateMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20|unique:users,phone,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'mobile');
        }

        $user = Auth::user();
        $user->update([
            'phone' => $request->phone,
        ]);

        return back()->with('mobile_success', 'Mobile number updated successfully!');
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'photo');
        }

        $user = Auth::user();

        // Delete old profile photo if exists
        if ($user->profile_photo_path && Storage::exists('public/' . $user->profile_photo_path)) {
            Storage::delete('public/' . $user->profile_photo_path);
        }

        // Store new profile photo
        $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');

        $user->update([
            'profile_photo_path' => $photoPath,
        ]);

        return back()->with('photo_success', 'Profile photo updated successfully!');
    }

    /**
     * Remove profile photo
     */
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path && Storage::exists('public/' . $user->profile_photo_path)) {
            Storage::delete('public/' . $user->profile_photo_path);
        }

        $user->update([
            'profile_photo_path' => null,
        ]);

        return back()->with('photo_success', 'Profile photo removed successfully!');
    }
}
