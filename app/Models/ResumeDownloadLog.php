<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeDownloadLog extends Model
{
    protected $fillable = [
        'visitor_token',
        'ip_hash',
        'country',
        'country_code',
        'city',
        'browser',
        'os',
        'device_type',
        'template',
        'referrer',
        'path',
        'download_count',
        'first_downloaded_at',
        'last_downloaded_at',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'download_count' => 'integer',
            'first_downloaded_at' => 'datetime',
            'last_downloaded_at' => 'datetime',
        ];
    }
}
