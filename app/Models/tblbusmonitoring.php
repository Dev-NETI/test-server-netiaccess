<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class tblbusmonitoring extends Model
{
    use HasFactory;
    protected $table = "tblbusmonitoring";
    protected $fillable = ['enroledid','scanned_by','chance_passenger', 'divideto' ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->scanned_by = Auth::user()->full_name;
        });
    }
    

    public function enroled(){
        return $this->belongsTo(tblenroled::class, 'enroledid', 'enroledid');
    }

    // acessor
    public function getChancePassengerDescAttribute()
    {
        return $this->chance_passenger === 0 ? 'No' : 'Kindly update transportation!';
    }
}
