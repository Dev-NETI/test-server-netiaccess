<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpdecertserialnumber extends Model
{
    protected $table = "tblpdecertserialnumber";
    protected $primaryKey ="id";
    protected $fillable=['SerialNumber'];
    use HasFactory;
}
