<?php

namespace App\Models\Experience;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    protected $fillable = [
        'role',
        'company',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'sort_order',
        'is_active',
        'is_for_resume',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'is_active' => 'boolean',
            'is_for_resume' => 'boolean',
        ];
    }

    public function responsibilities(): HasMany
    {
        return $this->hasMany(ExperienceResponsibility::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function scopeForResume(Builder $query): Builder
    {
        return $query->where('is_for_resume', true);
    }
}
