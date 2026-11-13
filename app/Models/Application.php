<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'application';
    protected $fillable = [
        'job_post_id',
        'seeker_id',
        'resume_id',
        'cover_letter',
        'status',
        'applied_at',
    ];

    public function jobPost() {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function seeker(){
        return $this->belongsTo(SeekersProfile::class, 'seeker_id');
    }

    public function resume() {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
