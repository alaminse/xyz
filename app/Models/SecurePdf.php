<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SecurePdf extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'file_path', 'original_name',
        'slug', 'category', 'total_pages', 'file_size',
        'is_active', 'isPaid', 'allow_print',
        'chapter_id', 'lesson_id', 'created_by',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'isPaid'      => 'boolean',
        'allow_print' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->title);
                $slug = $base;
                $i    = 1;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_secure_pdf');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accessLogs()
    {
        return $this->hasMany(PdfAccessLog::class);
    }

    public function viewTokens()
    {
        return $this->hasMany(PdfViewToken::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' Bytes';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active
            ? '<span class="badge badge-success">Active</span>'
            : '<span class="badge badge-danger">Inactive</span>';
    }
}
