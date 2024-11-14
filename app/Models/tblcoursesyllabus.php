<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcoursesyllabus extends Model
{
    protected $table = 'tblcoursesyllabus';
    protected $primaryKey = 'id';
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(tblcourses::class , 'courseid' , 'courseid');
    }
}
