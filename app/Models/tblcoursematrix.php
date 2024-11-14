<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcoursematrix extends Model
{
    protected $table = 'tblcoursematrix';
    protected $primaryKey = 'id';
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(tblcourses::class , 'courseid' , 'courseid');
    }
}
