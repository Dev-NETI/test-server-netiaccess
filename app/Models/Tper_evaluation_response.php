<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tper_evaluation_response extends Model
{
    use HasFactory;
    protected $fillable = [
        'tper_question_id' , 
        'enroled_id' , 
        'response'
    ];
}
