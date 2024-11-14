<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class billingattachment extends Model
{
    protected $table = 'billingattachment';
    protected $primarykey = 'id';
    protected $fillable = ['is_deleted'];
    use HasFactory;

    public function attachmenttype()
    {
        return $this->belongsTo(billingattachmenttype::class , 'attachmenttypeid');
    }

    public function schedule()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid', 'scheduleid');
    }
}
