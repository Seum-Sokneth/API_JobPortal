<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
     protected $table = 'companies';

    protected $fillable = [
        'name',
        'logo_company',
        'title',
        'total_employee',
        'location',
        'contact',
        'status',
        'website',
    ];

    // public function employer()
    // {
    //     return $this->belongsTo(User::class, 'employer_id');
    // }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'company_id');
    }
}
