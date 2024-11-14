<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbltrainingreport extends Model
{
    protected $table = 'tbltrainingreport';
    protected $fillable = ['scheduleid', 'Q1_a', 'Q1_b', 'Q2', 'Q3', 'isOthers', 'isSPER', 'isCAS', 'isTR', 'isTFF', 'isOtherForms'];
    use HasFactory;
    
}
