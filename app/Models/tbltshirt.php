<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbltshirt extends Model
{
    protected $table = 'tbltshirt';
    protected $primaryKey = 'tshirtid';
    use HasFactory;
}
