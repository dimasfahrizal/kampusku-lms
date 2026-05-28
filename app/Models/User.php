<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Properti Fillable Terpadu (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Memastikan role bisa diisi saat register/create user
    ];

    /**
     * Properti Hidden (Menyembunyikan data sensitif)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi Many-to-Many ke Mata Kuliah
     */
    public function courses() 
    {
        return $this->belongsToMany(Course::class);
    }
    
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    /**
     * Helper Check Role Dosen
     */
    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    /**
     * Helper Check Role Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * GERBANG UTAMA FILAMENT PANEL (Hanya 1 Fungsi, Tidak Duplikat)
     * Mengizinkan Admin dan Dosen untuk mengelola panel admin Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Jika emailnya admin@kampus.com ATAU rolenya admin ATAU rolenya dosen, berikan izin masuk
        return $this->email === 'admin@kampus.com' || $this->isAdmin() || $this->isDosen();
        return $this->role !== 'student';
    }
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}