<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljisstemplatesxycoordinates extends Model
{
    use HasFactory;
    protected $table = 'tbljisstemplatexycoordinates';
    protected $fillable = [
        'courseid',
        'sn_cds',
        'recipients_cds',
        'recipientcompany_cds',
        'recipientposition_cds',
        'recipientaddressline1_cds',
        'recipientaddressline2_cds',
        'datebilled_cds',
        'trainingtitle_cds',
        'course_cds',
        'trainees_cds',
        'nationality_cds',
        'amount_cds',
        'total_cds',
        'meal_cds',
        'transpo_cds',
        'dorm_cds',
        'dmt_cds',
        'servicechange_cds',
        'overalltotal_cds',
        'signature1_cds',
        'signature2_cds',
        'signature3_cds',
        'deletedid'
    ];

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }
}
