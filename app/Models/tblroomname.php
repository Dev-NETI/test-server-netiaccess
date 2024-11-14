<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblroomname extends Model
{
    protected $table = 'tblroomname';
    protected $fillable = ['capacity', 'deleteid','roomname', 'roomtypeid', 'max_capacity'];
    use HasFactory;

    public function roomtype(){
        return  $this->belongsTo(tblroomtype::class, 'roomtypeid', 'id');
    }

}
