<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeekersProfile extends Model
{
    protected $table = 'seekers_profiles';
    protected $fillable = [
        'user_id',
        'fullname',
        'email',
        'password',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resume()
    {
        return $this->hasOne(Resume::class, 'seeker_id');
    }
}
