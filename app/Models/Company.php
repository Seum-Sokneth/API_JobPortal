<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Company extends BaseModel
{
    protected $table = 'companies';

    protected $fillable = [
        'employer_id',
        'name',
        'logo_company',
        'title',
        'total_employee',
        'location',
        'contact',
        'status',
        'website',
    ];

    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute(): ?string
    {
        $logoPath = $this->getRawOriginal('logo_company');

        return $logoPath ? Storage::disk('public')->url($logoPath) : null;
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function jobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class, 'company_id');
    }
}
