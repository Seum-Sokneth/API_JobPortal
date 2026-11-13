<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
   protected $table = 'resume';
   protected $fillable = [
        'seeker_id',
        'fullname',
        'email',
        'phone',
        'file_path',
    ];
    public function seeker() {
        return $this->belongsTo(SeekersProfile::class, 'seeker_id');
    }

    public function applications(){
        return $this->hasMany(Application::class, 'resume_id');
    }
}
