<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDepartement extends Model
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
