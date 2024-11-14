<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcompanycourse extends Model
{
    protected $table = 'tblcompanycourse';
    protected $primaryKey = 'companycourseid';
    protected $fillable = [
        'companyid',
        'courseid',
        'ratepeso',
        'deletedid',
        'rateusd',
        'meal_price_peso',
        'meal_price_dollar',
        'dorm_price_peso',
        'dorm_price_dollar',
        'dorm_2s_price_peso',
        'dorm_2s_price_dollar',
        'dorm_4s_price_peso',
        'dorm_4s_price_dollar',
        'transpo_fee_peso',
        'transpo_fee_dollar',
        'billing_statement_format',
        'billing_statement_template',
        'default_currency',
        'bank_charge',
        'wDMT'
    ];
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'companyid');
    }

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }
}
