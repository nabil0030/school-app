<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class ProfileController extends Controller
{
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'cin_image' => 'required|image|mimes:jpg,jpeg,png',
            'bac_image' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $student = Student::find($request->student_id);

        $cinPath = $request->file('cin_image')->store('cin', 'public');
        $bacPath = $request->file('bac_image')->store('bac', 'public');

        $student->cin_image = $cinPath;
        $student->bac_image = $bacPath;
        $student->save();

        return response()->json([
            'message' => 'Documents uploaded successfully',
            'cin' => $cinPath,
            'bac' => $bacPath,
        ]);
    }
    public function verify($id)
{
    $student = Student::findOrFail($id);

    if ($student->cin_image && $student->bac_image) {
        $student->status = 'pending_verification';
    } else {
        $student->status = 'incomplete';
    }

    $student->save();

    return response()->json($student);
}

}
