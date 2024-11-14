<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class tblcourseschedule extends Model
{
    protected $table = 'tblcourseschedule';
    protected $primaryKey = 'scheduleid';
    use HasFactory;

    protected $fillable = [
        'batchno',
        'courseid',
        'coursecode',
        'startdateformat',
        'enddateformat',
        'dateonsitefrom',
        'dateonsiteto',
        'dateonlinefrom',
        'dateonlineto',
    ];

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid', 'courseid');
    }

    public function instructor()
    {
        return $this->belongsTo(tblinstructor::class, 'instructorid', 'userid');
    }

    public function altinstructor()
    {
        return $this->belongsTo(tblinstructor::class, 'alt_instructorid', 'userid');
    }

    public function assessor()
    {
        return $this->belongsTo(tblinstructor::class, 'assessorid', 'userid');
    }

    public function altassessor()
    {
        return $this->belongsTo(tblinstructor::class, 'alt_assessorid', 'userid');
    }

    public function room()
    {
        return $this->belongsTo(tblroom::class, 'roomid', 'roomid');
    }

    public function count_enrolled()
    {
        return $this->hasMany((tblenroled::class));
    }

    public function ins_license()
    {
        return $this->belongsTo(tblinstructorlicense::class, 'instructorlicense', 'instructorlicense');
    }

    public function asses_license()
    {
        return $this->belongsTo(tblinstructorlicense::class, 'assessorlicense', 'instructorlicense');
    }

    public function enroll()
    {
        return $this->hasMany(tblenroled::class, 'scheduleid', 'scheduleid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'instructorid', 'user_id');
    }

    // ENCAPSULATION
    public function getTrainingDateAttribute()
    {
        $startDate = Carbon::parse($this->startdateformat);
        $endDate = Carbon::parse($this->enddateformat);
        if ($this->dateonsitefrom == $this->dateonsiteto) {
            return $startDate->format('M. d, Y');
        } else {

            return $startDate->format('M. d, Y') . " to " . $endDate->format('M. d, Y');
        }
    }

    public function billing_attachment()
    {
        return $this->hasMany(billingattachment::class, 'scheduleid', 'scheduleid');
    }

    public function getScheduleWithTrainingDateAttribute()
    {
        return $this->course->coursecode . " " . $this->course->coursename . " " . $this->getTrainingDateAttribute();
    }

    public function getDropdownNameAttribute()
    {
        return $this->batchno;
    }

    public function getDropdownIdAttribute()
    {
        return $this->batchno;
    }

    public function getPracticalAttribute()
    {
        $startDate = Carbon::parse($this->dateonsitefrom);
        $endDate = Carbon::parse($this->dateonsiteto);
        if ($this->dateonsitefrom == null || $this->dateonsitefrom == '0000-00-00') {
            return 'NO PRACTICAL TRAINING';
        }
        if ($this->dateonsitefrom == $this->dateonsiteto) {
            return $startDate->format('M d, Y');
        } else {
            return $startDate->format('M d, Y') . " to " . $endDate->format('M d, Y');
        }
    }

    public function getOnlineAttribute()
    {
        $startDate = Carbon::parse($this->dateonlinefrom);
        $endDate = Carbon::parse($this->dateonlineto);

        if ($this->dateonlinefrom == null || $this->dateonlineto == '0000-00-00') {
            return 'NO ONLINE TRAINING';
        }

        if ($this->dateonlinefrom == $this->dateonlineto) {
            return $startDate->format('M d, Y');
        } else {
            return $startDate->format('M d, Y') . " to " . $endDate->format('M d, Y');
        }
    }
}
