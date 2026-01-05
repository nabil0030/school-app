<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentStatusController extends Controller
{
    public function show($id)
    {
        $student = Student::findOrFail($id);

        return response()->json([
            'status' => $student->status,
            'cin_image' => $student->cin_image 
                ? asset('storage/' . $student->cin_image) 
                : null,
            'bac_image' => $student->bac_image 
                ? asset('storage/' . $student->bac_image) 
                : null,
        ]);
    }
}
