<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbllogs extends Model
{
    protected $table = 'tbllogs';
    protected $primaryKey = 'log_id';

    protected $casts = [
        'data' => 'array',
    ];
    use HasFactory;
}
