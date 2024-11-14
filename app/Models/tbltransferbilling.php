<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbltransferbilling extends Model
{
    use HasFactory;
    protected $table = 'tbltransferbilling';
    protected $fillable = ['enroledid', 'scheduleid','sig1','sig2','sig3', 'notes_comments','serialnumber', 'traineeid', 'datebilled', 'created_by', 'vesselid', 'payeecompanyid', 'billingstatusid'];

    public function traineeinfo()
    {
        return $this->belongsTo(tbltraineeaccount::class, 'traineeid');
    }

    public function scheduleinfo()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }

    public function companyinfo()
    {
        return $this->belongsTo(tblcompany::class, 'payeecompanyid', 'companyid');
    }

    public function enroledinfo()
    {
        return $this->belongsTo(tblenroled::class, 'enroledid');
    }

    public function vesselinfo()
    {
        return $this->belongsTo(tblvessels::class, 'vesselid');
    }
}
