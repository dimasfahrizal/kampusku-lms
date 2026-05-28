<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Akun Dosen Simulator
        User::updateOrCreate(
            ['email' => 'dosen@kampusku.com'],
            [
                'name' => 'Dr. Budi Santoso, M.T.',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
            ]
        );

        // Buat Akun Mahasiswa Simulator (Opsi Tambahan)
        User::updateOrCreate(
            ['email' => 'mahasiswa@kampusku.com'],
            [
                'name' => 'Ahmad Rafli',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]
        );
    }
}
