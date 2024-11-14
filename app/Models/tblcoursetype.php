<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcoursetype extends Model
{
    protected $table = 'tblcoursetype';
    protected $primaryKey = 'coursetypeid';
    use HasFactory;

    public function courses()
    {
        return $this->hasMany(tblcourses::class,'coursetypeid');
    }
}
