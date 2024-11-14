<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    use HasFactory;

    public function adminroles()
    {
        return $this->hasMany(adminroles::class , 'id' , 'role_id');
    }

}
