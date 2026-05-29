<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
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
        'referrer',
        'path',
        'visit_count',
        'first_seen_at',
        'last_seen_at',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'visit_count' => 'integer',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }
}
