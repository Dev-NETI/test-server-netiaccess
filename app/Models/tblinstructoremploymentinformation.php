<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinstructoremploymentinformation extends Model
{
    protected $table = 'tblinstructoremploymentinformation';
    protected $primaryKey = 'employmentinformationid';

    protected $fillable = [
        'rank','vessel','vesseltype','inclusivedate','instructorid'
    ];

    use HasFactory;
}
