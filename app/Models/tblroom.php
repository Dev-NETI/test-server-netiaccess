<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblroom extends Model
{
    protected $table = 'tblroom';
    protected $primaryKey = 'roomid';
    protected $fillable=['room','deleteid'];
    use HasFactory;
}
