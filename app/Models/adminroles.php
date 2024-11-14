<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adminroles extends Model
{
    protected $table = 'adminroles';
    protected $primaryKey = 'id';
    use HasFactory;

    public function roles()
    {
        return $this->belongsTo(roles::class , 'role_id' , 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id' , 'user_id');
    }

}
