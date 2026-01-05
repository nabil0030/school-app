<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function show($id)
    {
        if (!session('admin_id')) {
            return redirect('/admin/login');
        }

        $student = Student::findOrFail($id);

        return view('admin.student_show', compact('student'));
    }

    public function accept($id)
    {
        Student::where('id', $id)->update([
            'status' => 'accepted',
            'admin_comment' => null
        ]);

        return redirect('/admin/dashboard');
    }

    public function reject(Request $request, $id)
    {
        Student::where('id', $id)->update([
            'status' => 'rejected',
            'admin_comment' => $request->comment
        ]);

        return redirect('/admin/dashboard');
    }
}
