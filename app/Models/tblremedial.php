<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblremedial extends Model
{
    protected $table = 'tblremedials';
    use HasFactory;


    public function schedule()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }
}
