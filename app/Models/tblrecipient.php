<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblrecipient extends Model
{
    use HasFactory;
    protected $table = 'tblrecipient';
    protected $primaryKey ='recipientid';
    protected $fillable = ['wholename','mobilenumber','message'];
    use HasFactory;
}

