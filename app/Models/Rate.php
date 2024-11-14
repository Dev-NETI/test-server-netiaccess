<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table  = 'rates';
    protected $primaryKey = 'id';


    public function rate()
    {
        return $this->belongsTo(Rate_Dropdown::class, 'rate_id');
    }

    public function rank()
    {
        return $this->belongsTo(tblrank::class, 'rank_id', 'rankid');
    }
}
