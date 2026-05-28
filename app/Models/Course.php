<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Course extends Model
{
    //
    protected $fillable = [
    'name',
    'slug',
    'about',
    'thumbnail',
    'category_id',
    'invite_code',
    ];

    protected static function booted()
    {
        static::creating(function ($course) {
            // Otomatis membuat kode acak unik berformat uppercase (Contoh: KMP4X2) sebelum tersimpan
            do {
                $code = strtoupper(Str::random(6));
            } while (static::where('invite_code', $code)->exists()); // Pastikan tidak kembar di DB

            $course->invite_code = $code;
        });
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function assignments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function announcements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function forums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Forum::class);
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function users()
    {
        // Menghubungkan Course ke banyak User (Dosen)
        return $this->belongsToMany(User::class);
    }
}
    