<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Hidden(['password'])]
class SeekersProfile extends BaseModel
{
    protected $table = 'seekers_profiles';

    protected $fillable = [
        'user_id',
        'fullname',
        'email',
        'password',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resume()
    {
        return $this->hasOne(Resume::class, 'seeker_id');
    }

    /**
     * Get the attribute casting rules.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
