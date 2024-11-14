<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblforeignrate extends Model
{
    use HasFactory;
    protected $table = 'tblforeignrate';
    protected $fillable = [
        'companyid',
        'courseid',
        'course_rate',
        'bf_rate',
        'lh_rate',
        'dn_rate',
        'dorm_am_checkin',
        'dorm_pm_checkin',
        'dorm_am_checkout',
        'dorm_pm_checkout',
        'dorm_rate',
        'meal_rate',
        'transpo',
        'bank_charge',
        'format',
        'template',
        'deletedid'
    ];

    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'companyid');
    }

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }

    public function schedule()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }


    //Functions CRUD
    public static function createRate(array $data)
    {
        return static::create($data);
    }

    public static function updateRate(array $data){
        $table = static::find($data['id']);
        return $table->update([
            'companyid' => $data['companyid'],
            'courseid' => $data['courseid'],
            'course_rate' => $data['course_rate'],
            'bf_rate' => $data['bf_rate'],
            'lh_rate' => $data['lh_rate'],
            'dn_rate' => $data['dn_rate'],
            'dorm_am_checkin' => $data['dorm_am_checkin'],
            'dorm_pm_checkin' => $data['dorm_pm_checkin'],
            'dorm_am_checkout' => $data['dorm_am_checkout'],
            'dorm_pm_checkout' => $data['dorm_pm_checkout'],
            'dorm_rate' => $data['dorm_rate'],
            'meal_rate' => $data['meal_rate'],
            'transpo' => $data['transpo'],
            'bank_charge' => $data['bank_charge'],
            'format' => $data['format'],
            'template' => $data['template'],
        ]);
    }

    public static function deleteRate($id)
    {
        $table = static::find($id);
        return $table->update([
            'deletedid' => 1
        ]);
    }
    
}
