<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfAccessLog extends Model
{
    protected $fillable = [
        'secure_pdf_id', 'user_id',
        'ip_address', 'user_agent',
        'action', 'accessed_at',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function securePdf()
    {
        return $this->belongsTo(SecurePdf::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
