<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $admin = Student::where('email', $request->email)
                        ->where('role', 'ADMIN')
                        ->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        session(['admin_id' => $admin->id]);

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        session()->forget('admin_id');
        return redirect('/admin/login');
    }
}
