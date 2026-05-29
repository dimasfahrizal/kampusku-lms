<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Course;


class DashboardController extends Controller
{
    
    /**
     * Menampilkan halaman Dashboard Utama Mahasiswa
     */
    public function index()
    {
        $user = Auth::user();


        // 1. Mengambil semua kelas (courses) yang diikuti oleh mahasiswa beserta tugasnya
        $courses = $user->courses()->with(['assignments', 'category'])->get();

        // 2. Mengumpulkan semua ID tugas dari kelas-kelas yang diikuti mahasiswa
        $assignmentIds = $courses->flatMap(function ($course) {
            return $course->assignments->pluck('id');
        });

        // 3. Filter Tugas/Kuis yang Harus Dikerjakan (Belum disubmit oleh mahasiswa & belum melewati deadline)
        $submittedAssignmentIds = Submission::where('user_id', $user->id)->pluck('assignment_id')->toArray();
        
        $pendingAssignments = Assignment::whereIn('id', $assignmentIds)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->where('deadline', '>', now())
            ->orderBy('deadline', 'asc')
            ->get();

        // 4. MENGHITUNG NILAI RATA-RATA (Hanya dari kuis/tugas yang sudah dinilai oleh dosen)
        $averageScore = Submission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->avg('score');
            
        $averageScore = $averageScore ? round($averageScore, 1) : 0;

        // 5. MENGHITUNG PROGRESS KELAS (Rasio tugas dikerjakan vs total semua tugas)
        $totalAssignmentsCount = Assignment::whereIn('id', $assignmentIds)->count();
        $doneAssignmentsCount = count($submittedAssignmentIds);
        
        $progressPercentage = $totalAssignmentsCount > 0 
            ? round(($doneAssignmentsCount / $totalAssignmentsCount) * 100) 
            : 0;

        $studentSubmissions = Submission::where('user_id', $user->id)
            ->with('assignment')
            ->orderBy('created_at', 'desc')
            ->get();
        // Mengirimkan semua variabel ke view 'dashboard' bawaan kamu
        return view('dashboard', compact(
            'courses', 
            'pendingAssignments', 
            'averageScore', 
            'progressPercentage',
            'studentSubmissions'
        ));
    }

    /**
     * Menampilkan halaman Cetak Laporan Belajar (Fitur Minggu 3)
     */
    public function report()
    {
        // Mengambil data user yang sedang login beserta kursus yang diambil
        $user = Auth::user();
        $courses = $user->courses()->with('category')->get();

        // Mengirim data ke halaman khusus cetak
        return view('dashboard-report', compact('user', 'courses'));
    }

    public function showAssignment(Assignment $assignment)
    {
        // Memastikan mata kuliah tugas ini memang diikuti oleh mahasiswa yang sedang login
        return view('student.assignments.show', compact('assignment'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file_submission' => 'required|file|mimes:pdf,doc,docx,zip|max:5120', // Maksimal 5MB
        ]);

        // Proses simpan file ke storage
        $filePath = $request->file('file_submission')->store('submissions', 'public');

        // Simpan ke tabel jawaban (Asumsi nama model: AssignmentAnswer atau sejenisnya)
        // Jika belum ada tabel jawaban, sementara kita return success dulu untuk tes halaman
        Submission::create([
            'assignment_id' => $assignment->id,
            'user_id'       => auth()->id(),
            'file'          => $filePath, // 💡 Catatan: Jika nanti muncul error kolom 'file' tidak ditemukan, ganti kata 'file' menjadi 'file_url' atau 'file_path' sesuai database kamu.
        ]);

        return redirect()->route('dashboard')->with('success', 'Tugas berhasil dikirim! Menunggu penilaian dosen.');
    }
    
    public function joinClass(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|max:10',
        ]);

        // 1. Cari kelas berdasarkan kode invite yang diinput mahasiswa
        $course = Course::where('invite_code', strtoupper($request->invite_code))->first();

        // Jika kelas tidak ditemukan
        if (!$course) {
            return redirect()->back()->with('error', 'Kode undangan tidak valid atau kelas tidak ditemukan.');
        }

        $user = auth()->user();

        // 2. Cek apakah mahasiswa sudah bergabung di kelas ini sebelumnya
        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return redirect()->back()->with('error', 'Kamu sudah bergabung di dalam mata kuliah ini.');
        }

        // 3. Daftarkan mahasiswa ke kelas (Asumsi nama relasi di Model User adalah courses())
        $user->courses()->attach($course->id);

        return redirect()->route('dashboard')->with('success', "Berhasil bergabung ke mata kuliah: {$course->name}!");
    }

    public function submitQuiz(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $user = auth()->user();

        if ($user->submissions()->where('assignment_id', $assignment->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'Kamu sudah mengerjakan kuis ini sebelumnya.');
        }

        $submittedAnswers = $request->input('answers', []); 
        $quizQuestions = $assignment->quiz_questions;       

        $totalQuestions = is_array($quizQuestions) ? count($quizQuestions) : 0;
        $correctCount = 0;

        if ($totalQuestions > 0) {
            foreach ($quizQuestions as $index => $question) {
                // 💡 Penyesuaian key: Menggunakan 'correct_answer' sesuai hasil dump database
                $correctAnswer = $question['correct_answer'] ?? ''; 
                
                if (isset($submittedAnswers[$index]) && $submittedAnswers[$index] === $correctAnswer) {
                    $correctCount++;
                }
            }
            $finalScore = round(($correctCount / $totalQuestions) * 100);
        } else {
            $finalScore = 0;
        }

        $user->submissions()->create([
            'assignment_id' => $assignment->id,
            'status'        => 'graded', 
            'score'         => $finalScore,
            'feedback'      => "Auto-graded: Benar {$correctCount} dari {$totalQuestions} soal.",
        ]);

        return redirect()->route('dashboard')->with('success', "Kuis berhasil dikirim! Nilai kamu: {$finalScore}");
    }
}