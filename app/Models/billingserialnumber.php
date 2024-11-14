<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class billingserialnumber extends Model
{
    protected $table = 'billingserialnumber';
    protected $primarykey = 'id';
    protected $fillable = ['dateformat','serialnumber'];

    use HasFactory;
}
