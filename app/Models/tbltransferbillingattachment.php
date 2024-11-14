<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbltransferbillingattachment extends Model
{
    use HasFactory;
    protected $table = 'tbltransferbillingattachment';
    protected $fillable = ['scheduleid', 'enroledid', 'filepath', 'title', 'attachmenttypeid', 'posted_by'];

    public function sheduleinfo()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }

    public function enroledinfo()
    {
        return $this->belongsTo(tblenroled::class, 'enroledid');
    }

    public function attachmenttype()
    {
        return $this->belongsTo(billingattachmenttype::class, 'attachmenttypeid');
    }
}
