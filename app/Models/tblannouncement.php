<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblannouncement extends Model
{
    protected $table = 'tblannouncement';
    protected $primaryKey = 'announcementid';
    use HasFactory;
}
