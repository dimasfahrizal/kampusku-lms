<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;

class KampuskuSeeder extends Seeder
{
    public function run(): void
    {
    // 1. Buat Role
        $ownerRole = Role::create(['name' => 'owner']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        // 2. Berikan Role ke user yang sudah ada (misal akun 'ragil')
        // Atau buat user admin baru khusus
        $user = User::create([
            'name' => 'Admin Kampus',
            'email' => 'admin@kampus.com',
            'password' => bcrypt('password123'),
        ]);
        
        $user->assignRole($ownerRole);        
        // 1. Buat Kategori
        $category = Category::create([
            'name' => 'Pemrograman Web',
            'slug' => Str::slug('Pemrograman Web'),
        ]);

        // 2. Buat Mata Kuliah (Course)
        $course = Course::create([
            'name' => 'Belajar Laravel 11 untuk Pemula',
            'slug' => Str::slug('Belajar Laravel 11 untuk Pemula'),
            'about' => 'Kursus ini membahas dasar-dasar Laravel hingga mahir.',
            'thumbnail' => 'https://laravel.com/img/logomark.min.svg',
            'category_id' => $category->id,
        ]);

        // 3. Buat Modul/Materi
        Module::create([
            'name' => 'Pengenalan Routing',
            'slug' => Str::slug('Pengenalan Routing'),
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'course_id' => $course->id,
        ]);

        Module::create([
            'name' => 'Database Migration',
            'slug' => Str::slug('Database Migration'),
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'course_id' => $course->id,
        ]);
    }
}