<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();
        $courses = Course::with('category')->latest()->get();
        
        return view('front.index', compact('categories', 'courses'));
    }

    public function details($slug)
    {
        $course = Course::with(['category', 'modules'])->where('slug', $slug)->firstOrFail();
        return view('front.details', compact('course'));
    }

    public function enroll($slug)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $course = Course::where('slug', $slug)->firstOrFail();
        
        // Mendaftarkan mahasiswa ke kursus ini (menyimpan ke tabel pivot course_user)
        // syncWithoutDetaching mencegah duplikasi pendaftaran
        Auth::user()->courses()->syncWithoutDetaching($course->id);

        // Kembali ke halaman detail
        return redirect()->route('dashboard')->with('success', 'Selamat! Kamu berhasil mendaftar di mata kuliah ini. Yuk, mulai belajar!');
    }
}
