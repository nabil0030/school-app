<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function signup(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:students',
            'cin' => 'required|unique:students',
            'password' => 'required|min:6',
        ]);

        $student = Student::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'cin' => $validated['cin'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Signup success',
            'student' => $student,
        ], 201);
    }
}
