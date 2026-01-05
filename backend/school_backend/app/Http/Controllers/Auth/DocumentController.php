<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\OcrService;

class DocumentController extends Controller
{
    // ================= OCR CIN =================
    public function uploadCin(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'cin_image'  => 'required|image|max:4096',
        ]);

        $student = Student::where('id', $request->student_id)->firstOrFail();

        // Store image
        $path = $request->file('cin_image')->store('cin_images', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // OCR
        $text = OcrService::extractText($fullPath);
        if (!$text) {
            return response()->json(['message' => 'OCR failed'], 422);
        }

        $cleanText = strtoupper(preg_replace('/\s+/', ' ', $text));

        //  CIN EXTRACTION
        preg_match('/[A-Z]{1,2}\d{5,7}/', $cleanText, $cinMatch);
        $ocrCin = $cinMatch[0] ?? '';

        //  DB VALUES
        $dbNom = strtoupper($student->nom);
        $dbPrenom = strtoupper($student->prenom);
        $dbCin = strtoupper($student->cin);

        $nomMatch = str_contains($cleanText, $dbNom);
        $prenomMatch = str_contains($cleanText, $dbPrenom);
        $cinMatchOk = ($dbCin === $ocrCin);

        return response()->json([
            'ocr' => [
                'nom' => $nomMatch ? $student->nom : '',
                'prenom' => $prenomMatch ? $student->prenom : '',
                'cin' => $ocrCin,
            ],
            'messages' => [
                'nom' => $nomMatch ? null : 'Veuillez entrer le vrai nom',
                'prenom' => $prenomMatch ? null : 'Veuillez entrer le vrai prénom',
            ],
            'match' => [
                'nom' => $nomMatch,
                'prenom' => $prenomMatch,
                'cin' => $cinMatchOk,
            ],
            'raw_text' => $cleanText
        ]);
    }

    // ================= SAVE OCR CORRECTION =================
    public function saveCorrection(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'nom' => 'required|string',
            'prenom' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        // ✅ SAFE SAVE
        if ($request->filled('nom')) {
            $student->nom = trim($request->nom);
        }

        if ($request->filled('prenom')) {
            $student->prenom = trim($request->prenom);
        }

        $student->save();

        return response()->json([
            'message' => 'Données mises à jour avec succès'
        ]);
    }

    // ================= UPDATE PROFILE  =================
    public function updateProfile(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'cin' => 'required|string',
            'cin_image' => 'nullable|image|max:4096',
        ]);

        $student = Student::where('id', $request->student_id)->firstOrFail();

        //  prevent empty overwrite
        if ($request->filled('nom')) {
            $student->nom = trim($request->nom);
        }

        if ($request->filled('prenom')) {
            $student->prenom = trim($request->prenom);
        }

        if ($request->filled('cin')) {
            $student->cin = trim($request->cin);
        }

        // image only if exists
        if ($request->hasFile('cin_image')) {
            $path = $request->file('cin_image')->store('cin_images', 'public');
            $student->cin_image = $path;
        }

        //  GUARANTEED SAVE
        $student->save();

        return response()->json([
            'message' => 'UPDATE_SUCCESS',
            'student' => [
                'id' => $student->id,
                'nom' => $student->nom,
                'prenom' => $student->prenom,
                'cin' => $student->cin,
                'cin_image' => $student->cin_image,
            ]
        ]);
    }
}
