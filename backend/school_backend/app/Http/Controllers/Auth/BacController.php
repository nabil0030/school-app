<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\OcrService;

class BacController extends Controller
{
    // ================= UPLOAD + OCR + SAVE IMAGE =================
    public function upload(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'bac_image'  => 'required|image|max:4096',
        ]);

        $student = Student::findOrFail($request->student_id);

        // ✅ STORE IMAGE
        $path = $request->file('bac_image')->store('bac_images', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // ✅ OCR
        $text = OcrService::extractText($fullPath);
        if (!$text) {
            return response()->json(['error' => 'OCR_FAILED'], 422);
        }

        $cleanText = strtoupper(preg_replace('/\s+/', ' ', $text));

        preg_match('/\d{8,12}/', $cleanText, $number);
        preg_match('/20\d{2}/', $cleanText, $year);
        preg_match('/(PASSABLE|ASSEZ BIEN|BIEN|TRES BIEN|TRÈS BIEN)/', $cleanText, $mention);

        // ✅ SAVE IMAGE PATH IMMEDIATELY
        $student->bac_image = $path;
        $student->save();

        return response()->json([
            'ocr' => [
                'bac_number'  => $number[0] ?? '',
                'bac_year'    => $year[0] ?? '',
                'bac_mention' => $mention[0] ?? '',
            ],
            'bac_image' => $path,
            'raw_text'  => $cleanText
        ]);
    }

    // ================= SAVE / CONFIRM TEXT =================
    public function save(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'bac_number'  => 'required|string',
            'bac_year'    => 'required|string',
            'bac_mention' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        $student->bac_number  = $request->bac_number;
        $student->bac_year    = $request->bac_year;
        $student->bac_mention = $request->bac_mention;

        $student->save();

        return response()->json(['message' => 'BAC_CONFIRMED']);
    }
}
