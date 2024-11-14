<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblroomtype extends Model
{
    public $table = 'tblroomtype';
    protected $fillable = ['deleteid', 'roomtype', 'capacity', 'nmcroomprice', 'nmcmealprice','mandatoryroomprice','mandatorymealprice'];
    use HasFactory;

    public function roomname(){
       return $this->hasMany(tblroomname::class, 'roomtypeid')->orderBy('roomname', 'ASC')->where('deleteid', 0);
    }
}
