<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPost extends Model
{
    //
    use SoftDeletes;
    protected $table = 'job_post';
      protected $fillable = [
        'company_id',
        'department_id',
        'post_date',
        'deadline',
        'employment_type',
        'experience_level',
        'minimum_salary',
        'maximum_salary',
        'about_role',
        'responsibilities',
        'required_qualifications',
        'tech_stack',
        'currency',
        'benefits',
        'status',
        'published_at',
    ];

    protected $casts = [
        'post_date' => 'datetime',
        'deadline' => 'datetime',
        'published_at' => 'datetime',

        'minimum_salary' => 'decimal:2',
        'maximum_salary' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(JobDepartement::class, 'department_id');
    }
}
