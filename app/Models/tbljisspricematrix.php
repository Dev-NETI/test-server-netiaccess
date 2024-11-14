<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljisspricematrix extends Model
{
    use HasFactory;
    protected $table = 'tbljisspricematrix';
    protected $fillable = [
        'companyid',
        'courseid',
        'PHP_USD',
        'courserate',
        'is_Deleted'
    ];

    public function course(){
        return $this->belongsTo(tbljisscourses::class, 'courseid');
    }

    public function company(){
        return $this->belongsTo(tbljisscompany::class, 'companyid');
    }
}
