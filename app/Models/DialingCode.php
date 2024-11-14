<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialingCode extends Model
{
    use HasFactory;

    public function trainee()
    {
        return $this->hasMany(tbltraineeaccount::class, 'dialing_code_id', 'id');
    }

    public function user()
    {
        return $this->hasMany(User::class , 'dialing_code_id ', 'id');
    }
}
