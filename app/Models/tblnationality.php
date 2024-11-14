<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblnationality extends Model
{
    protected $table = 'tblnationality';
    protected $primaryKey = 'nationalityid';
    use HasFactory;
}
