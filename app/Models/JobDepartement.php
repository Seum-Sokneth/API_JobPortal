<?php

namespace App\Models;

class JobDepartement extends BaseModel
{
    //
    protected $table = 'job_departement';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'department_id');
    }
}
