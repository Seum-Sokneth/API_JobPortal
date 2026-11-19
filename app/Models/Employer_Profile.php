<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer_Profile extends BaseModel
{
    protected $table = 'employer_profile';

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'user_id',
        'company_id',
        'company_name',
        'email',
        'phone',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class, 'employer_id');
    }
}
