<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCourseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test apakah API daftar kursus berjalan dengan baik dan mengembalikan JSON.
     */
    public function test_api_can_return_courses_list(): void
    {
        // 1. Robot pura-pura membuka URL API kita
        $response = $this->get('/api/courses');

        // 2. Memastikan status jaringannya 200 (OK / Berhasil)
        $response->assertStatus(200);

        // 3. Memastikan format balasannya benar-benar JSON dan memiliki status success = true
        $response->assertJson([
            'success' => true,
        ]);
    }
}