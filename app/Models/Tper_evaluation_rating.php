<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tper_evaluation_rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'tper_id' , 
        'enroled_id' , 
        'rating'
    ];

    public function enroled()
    {
        return $this->belongsTo(tblenroled::class , 'enroled_id', 'enroledid');
    }
}
