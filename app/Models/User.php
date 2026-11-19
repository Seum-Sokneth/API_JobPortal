<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory,Notifiable;

    public function employerProfile(): HasOne
    {
        return $this->hasOne(Employer_Profile::class, 'user_id');
    }

    public function seekerProfile(): HasOne
    {
        return $this->hasOne(SeekersProfile::class, 'user_id');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'employer_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return Carbon::instance($date)
            ->timezone(config('app.timezone'))
            ->format('Y-m-d\TH:i:s.uP');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
