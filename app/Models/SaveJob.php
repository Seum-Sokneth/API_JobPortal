<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaveJob extends BaseModel
{
    protected $table = 'savejob';

    protected $fillable = [
        'user_id',
        'job_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }
}
