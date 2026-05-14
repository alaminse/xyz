<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfViewToken extends Model
{
    protected $fillable = [
        'secure_pdf_id', 'user_id',
        'token', 'ip_address', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function securePdf()
    {
        return $this->belongsTo(SecurePdf::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
