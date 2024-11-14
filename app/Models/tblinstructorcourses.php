<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinstructorcourses extends Model
{
    protected $table = 'tblinstructorcourses';
    protected $primaryKey = 'instructorcoursesid';

    protected $fillable = [
        'userid','id','courseid'
    ];
    use HasFactory;

    public function courses()
    {
        return $this->belongsTo(tblcourses::class,'courseid');
    }

}
