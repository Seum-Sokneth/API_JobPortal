<?php

namespace App\Models;

class Resume extends BaseModel
{
    protected $table = 'resume';

    protected $fillable = [
        'seeker_id',
        'fullname',
        'email',
        'phone',
        'file_path',
    ];

    public function seeker()
    {
        return $this->belongsTo(SeekersProfile::class, 'seeker_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'resume_id');
    }
}
