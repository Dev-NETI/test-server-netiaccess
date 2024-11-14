<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class refbrgy extends Model
{
    protected $table = 'refbrgy';
    protected $primaryKey = 'id';

    use HasFactory;

    public function trainee(){
        return $this->hasMany(User::class, 'brgyCode', 'barangay');
    }
}
