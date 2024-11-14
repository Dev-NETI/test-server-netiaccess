<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpdeassessmentanswer extends Model
{
    use HasFactory;
    protected $table = "tblpdeassessmentanswer";
    protected $primaryKey = "id";
    protected $fillable=[
        'pdeid',
        'pderequirementsid',
        'is_Compliant',
        'remarks'
    ];
}
