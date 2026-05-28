<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    public function index()
    {
        // Mengambil semua data kursus beserta relasi kategorinya
        $courses = Course::with('category')->get();

        // Mengembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data daftar kursus',
            'data'    => $courses
        ]);
    }
}