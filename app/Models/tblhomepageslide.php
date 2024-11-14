<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblhomepageslide extends Model
{
    protected $table = 'tblhomepageslide';
    protected $primaryKey = 'homapageid';
    protected $fillable=['title','filepath','deletedid'];
    use HasFactory;
}
