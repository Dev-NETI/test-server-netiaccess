<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class password_reset_tokens_trainee extends Model
{
    protected $table = 'password_reset_tokens_trainee';
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
