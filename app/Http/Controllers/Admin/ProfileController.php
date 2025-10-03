<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the admin profile page
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update admin profile information
     */
    public function update(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            ]);

            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'data' => $admin->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            $request->validate([
                'current_password' => 'required',
                'new_password' => ['required', 'min:8'],
                'new_password_confirmation' => 'required|same:new_password',
            ], [
                'current_password.required' => 'Current password is required',
                'new_password.required' => 'New password is required',
                'new_password.min' => 'New password must be at least 8 characters long',
                'new_password_confirmation.required' => 'Please confirm your new password',
                'new_password_confirmation.same' => 'Password confirmation does not match',
            ]);

            // Check if current password is correct
            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 422);
            }

            // Update password
            $admin->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 422);
        }
    }
}