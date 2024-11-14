<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblvesseltype extends Model
{
    protected $table = 'tblvesseltype';
    protected $primaryKey = 'vesseltypeid';
    
    use HasFactory;
}
