<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbldormitoryreservation extends Model
{
    use Authenticatable;
    use HasFactory;
    protected $table = 'tbldormitoryreservation';
    protected $fillable = ['checkindate', 'is_reserved', 'roomid', 'checkintime'];


    public function paymentmode()
    {
        return $this->belongsTo(tblpaymentmode::class, 'paymentmodeid', 'paymentmodeid');
    }

    public function enroled()
    {
        return $this->belongsTo(tblenroled::class, 'enroledid', 'enroledid');
    }

    public function course()
    {
        return $this->belongsTo(tblenroled::class, 'courseid', 'courseid');
    }

    public function room()
    {
        return $this->belongsTo(tblroomname::class, 'roomid', 'id');
    }
}
