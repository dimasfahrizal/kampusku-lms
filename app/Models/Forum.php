<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forum extends Model
{
    protected $fillable = ['course_id', 'user_id', 'message', 'parent_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi untuk mengambil chat utama (induk)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'parent_id');
    }

    // Relasi untuk mengambil balasan dari chat ini
    public function replies(): HasMany
    {
        return $this->hasMany(Forum::class, 'parent_id');
    }
}