<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exists' => false,
                'error' => 'Invalid email format'
            ]);
        }

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email already registered' : 'Email available'
        ]);
    }

    public function checkMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exists' => false,
                'error' => 'Invalid mobile number'
            ]);
        }

        // Check if mobile exists in users table (using phone column)
        $exists = User::where('phone', $request->mobile)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Mobile number already registered' : 'Mobile number available'
        ]);
    }
}
