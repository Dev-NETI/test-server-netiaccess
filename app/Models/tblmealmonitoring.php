<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tblmealmonitoring extends Model
{
    use HasFactory;
    protected $table = "tblmealmonitoring";
    protected $fillable = ['enroledid', 'mealtype', 'created_date'];

    public function enrolinfo()
    {
        return $this->belongsTo(tblenroled::class, 'enroledid', 'enroledid');
    }


    // ASSESSORS

    public function getScannedTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('H:i:s');
    }

    public function getScannedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M. d, Y');
    }

    public function getMealTypeDescAttribute()
    {
        switch ($this->mealtype) {
            case 1:
                $meal = "Breakfast";
                break;
            case 2:
                $meal = "Lunch";
                break;
            case 3:
                $meal = "Dinner";
                break;
        }
        return $meal;
    }
}
