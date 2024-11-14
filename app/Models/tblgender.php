<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblgender extends Model
{
    protected $table = 'tblgender';
    protected $primarykey = 'genderid';
    use HasFactory;
}
