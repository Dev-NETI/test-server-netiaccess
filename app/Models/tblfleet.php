<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class tblfleet extends Model
{
    protected $table = 'tblfleet';
    protected $primaryKey = 'fleetid';
    protected $fillable=['fleet','fleetcode','pdecertnumber'];
    use HasFactory;

    public function fleet()
    {
        return $this->belongsTo(tblpde::class, 'fleetid', 'requestfleet');
    }

    // public function setHashDeletedAttribute($value)
    // {
    //  $this->attributes['fleet'] = Hash::make($value);
        
    // }
    
}
