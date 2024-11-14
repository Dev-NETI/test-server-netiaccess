<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcourses extends Model
{
    protected $table = 'tblcourses';
    protected $primaryKey = 'courseid';
    protected $fillable = ['coursecode', 'coursename', 'coursedepartmentid', 'coursetypeid', 'ranklevelid', 'vesseltypeid', 'instructorlicensetypeid', 'assessorlicensetypeid', 'trainingdays', 'minimumtrainees', 'maximumtrainees', 'courselocationid', '	modeofdeliveryid', 'deletedid'];
    use HasFactory;

    public function mode()
    {
        return $this->belongsTo(tblmodeofdelivery::class, 'modeofdeliveryid');
    }

    public function location()

    {
        return $this->belongsTo(tblcourselocation::class, 'courselocationid');
    }

    public function rank_level()
    {
        return $this->belongsTo(tblranklevel::class, 'ranklevelid');
    }

    public function course_depart()
    {
        return $this->belongsTo(tblrankdepartment::class, 'coursedepartmentid');
    }

    public function type()
    {
        return $this->belongsTo(tblcoursetype::class, 'coursetypeid');
    }

    public function schedules()
    {
        return $this->hasMany(tblcourseschedule::class, 'courseid', 'courseid');
    }

    public function certfontstyle()
    {
        return $this->belongsTo(tblfontstyle::class, 'certfontstyleid');
    }

    public function crewnamefontstyle()
    {
        return $this->belongsTo(tblfontstyle::class, 'crewnamefontstyleid');
    }

    public function birthdayfontstyle()
    {
        return $this->belongsTo(tblfontstyle::class, 'birthdayfontstyleid');
    }

    public function remarksfontstyle()
    {
        return $this->belongsTo(tblfontstyle::class, 'remarksfontstyleid');
    }

    public function coursedepartment()
    {
        return $this->belongsTo(tblcoursedepartment::class, 'coursedepartmentid');
    }
    public function vesseltype()
    {
        return $this->belongsTo(tblvesseltype::class, 'vesseltypeid');
    }

    // ENCAPSULATION
    public function getFullCourseNameAttribute()
    {
        return $this->coursecode . " - " . $this->coursename;
    }
}
