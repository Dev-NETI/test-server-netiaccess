<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljisscourses extends Model
{
    use HasFactory;
    protected $table = 'tbljisscourses';
    protected $fillable = [
        'coursename',
        'templateName',
        'templatePath'
    ];
}
