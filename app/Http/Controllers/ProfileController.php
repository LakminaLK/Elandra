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
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator, 'photo')->withInput();
            }

            $user = Auth::user();

            // Check if file was uploaded
            if (!$request->hasFile('profile_photo')) {
                return back()->withErrors(['profile_photo' => 'No file was uploaded.'], 'photo');
            }

            $file = $request->file('profile_photo');
            
            // Check if file is valid
            if (!$file->isValid()) {
                return back()->withErrors(['profile_photo' => 'The uploaded file is invalid.'], 'photo');
            }

            // Delete old profile photo if exists
            if ($user->profile_photo_path && Storage::exists('public/' . $user->profile_photo_path)) {
                Storage::delete('public/' . $user->profile_photo_path);
            }

            // Store new profile photo
            $photoPath = $file->store('profile-photos', 'public');

            if (!$photoPath) {
                return back()->withErrors(['profile_photo' => 'Failed to store the uploaded file.'], 'photo');
            }

            // Update user record
            $user->update([
                'profile_photo_path' => $photoPath,
            ]);

            return back()->with('photo_success', 'Profile photo updated successfully!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['profile_photo' => 'An error occurred during upload. Please try again.'], 'photo');
        }
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
