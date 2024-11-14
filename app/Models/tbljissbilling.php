<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljissbilling extends Model
{
    use HasFactory;
    protected $table = 'tbljissbilling';

    protected $fillable = [
        'company',
        'courseid',
        'trainees',
        'datebilled',
        'istraineenameincluded',
        'trainingtitle',
        'filepath',
        'month_covered',
        'vat_service_charge',
        'serialnumber',
        'billingstatusid',
        'approver_1',
        'approver_2',
        'approver_3',
        'viewed',
        'meal_expenses',
        'dorm_expenses',
        'transpo_expenses',
    ];

    public function course()
    {
        return $this->belongsTo(tbljisscourses::class, 'courseid');
    }

    public function matrix()
    {
        return $this->belongsTo(tbljisspricematrix::class, 'courseid');
    }

    public function companyinfo()
    {
        return $this->belongsTo(tbljisscompany::class, 'company');
    }
}
