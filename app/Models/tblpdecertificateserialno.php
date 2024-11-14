<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpdecertificateserialno extends Model
{
    protected $table = "tblpdecertificateserialno";
    protected $primaryKey ="id";
    protected $fillable=['pdecertificateserialno'];
    use HasFactory;
}
