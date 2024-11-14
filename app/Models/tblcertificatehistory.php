<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcertificatehistory extends Model
{

    protected $table = "tblcertificatehistory";
    protected $primaryKey = "certificatehistoryid";
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }

    public function trainee()
    {
        return $this->belongsTo(tbltraineeaccount::class, 'traineeid');
    }

    public function enrolled()
    {
        return $this->belongsTo(tblenroled::class, 'enroledid');
    }
}
