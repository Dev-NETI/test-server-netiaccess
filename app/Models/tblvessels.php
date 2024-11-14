<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblvessels extends Model
{
    use HasFactory;
    protected $table = 'tblvessels';
    protected $fillable = ['vesselname', 'deletedid'];

    public function trainee()
    {
        return $this->hasMany(tbltraineeaccount::class, 'vessel', 'id');
    }
}
