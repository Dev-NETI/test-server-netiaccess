<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinstructorlicense extends Model
{
    protected $table = 'tblinstructorlicense';
    protected $primaryKey = 'instructorlicense';
    protected $fillable = ['instructorid', 'license', 'issuingauthority', 'licensennumber', 'dateofissue', 'expirationdate', 'd_name', 'licensepath', 'l_size', 'instructorlicensetypeid'];
    use HasFactory;

    public function instructor()
    {
        return $this->belongsTo(tblinstructor::class, 'instructorid');
    }

    public function instructorlicensetype()
    {
        return $this->belongsTo(tblinstructorlicensetype::class, 'instructorlicensetypeid', 'instructorlicensetypeid');
    }
}
