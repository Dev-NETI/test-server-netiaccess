<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbldorm extends Model
{
    protected $table = 'tbldorm';
    protected $primaryKey = 'dormid';
    use HasFactory;
}
