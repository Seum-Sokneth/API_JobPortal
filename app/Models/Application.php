<?php

namespace App\Models;

class Application extends BaseModel
{
    protected $table = 'application';

    protected $fillable = [
        'seeker_id',
        'job_post_id',
        'job_post_id',
        'resume_id',
        'cover_letter',
        'file_path',
        'status',
        'applied_at',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function seeker()
    {
        return $this->belongsTo(SeekersProfile::class, 'seeker_id');
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
