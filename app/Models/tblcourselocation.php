<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcourselocation extends Model
{
    protected $table = 'tblcourselocation';
    protected $primaryKey = 'courselocationid';
    use HasFactory;
}
