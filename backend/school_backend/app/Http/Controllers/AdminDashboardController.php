<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
  
public function index(Request $request)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $status = $request->query('status');
    $search = $request->query('search');

    $students = Student::where('role', 'STUDENT')
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->when($search, function ($q) use ($search) {
            $q->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%$search%")
                      ->orWhere('prenom', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('cin', 'like', "%$search%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // 📊 Stats
    $stats = [
        'pending' => Student::where('role', 'STUDENT')->where('status', 'pending')->count(),
        'accepted' => Student::where('role', 'STUDENT')->where('status', 'accepted')->count(),
        'rejected' => Student::where('role', 'STUDENT')->where('status', 'rejected')->count(),
    ];

    return view('admin.dashboard', compact('students', 'status', 'search', 'stats'));
}
}
