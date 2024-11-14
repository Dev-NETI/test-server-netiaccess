<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trainee_lists extends Model
{
    protected $connection = 'second_database';
    protected $table = 'trainee_lists';
    use HasFactory;

    protected $fillable = [
    'enroledid',    
    'trainee_id',
    'enroled_id',
    'rank',
    'lastname',
    'firstname',
    'middlename',
    'suffix',
    'course',
    'course_code',
    'course_type',
    'company',
    'bus',
    'dorm',
    'training_start_date',
    'training_end_date',
    ];
}
