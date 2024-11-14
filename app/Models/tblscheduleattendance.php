<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblscheduleattendance extends Model
{
    use HasFactory;

    protected $table = "tblscheduleattendance";
    protected $fillable = [
        'scheduleid',
        'traineeid',
        'date',
        'present_am',
        'present_pm',
        'absent_am',
        'absent_pm',
        'noshow_am',
        'noshow_pm',
        'cancel_am',
        'cancel_pm',
        'drop_am',
        'drop_pm',
    ];
    
    public function trainee(){
        return $this->belongsTo( tbltraineeaccount::class, 'traineeid', 'traineeid');
    }

    public function schedule(){
        return $this->belongsTo( tblcourseschedule::class, 'scheduleid', 'scheduleid');
    }
}
