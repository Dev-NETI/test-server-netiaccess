<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll_period extends Model
{
    use HasFactory;
    protected $table = "payroll_periods";


    protected $fillable = [
        'period_start',
        'period_end',
        'hash_id',
    ];


    public function format_date()
    {
        return Carbon::parse($this->period_start)->format('F d, Y') . ' - ' . Carbon::parse($this->period_end)->format('F d, Y');
    }
}
