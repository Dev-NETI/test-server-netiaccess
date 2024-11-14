<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcoursedepartment extends Model
{
    protected $table = 'tblcoursedepartment';
    protected $primaryKey = 'coursedepartmentid';
    use HasFactory;
}
