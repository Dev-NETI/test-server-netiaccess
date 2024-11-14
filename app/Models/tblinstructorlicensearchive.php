<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinstructorlicensearchive extends Model
{
    protected $table = 'tblinstructorlicensearchive';
    protected $primaryKey = 'instructorlicense';

    protected $fillable = ['instructorid', 'license', 'issuingauthority','licensenumber','dateofissue','expirationdate','licensepath','l_size','instructorlicensetypeid'];
    use HasFactory;

    public function instructorlicensetype()
    {
        return $this->belongsTo(tblinstructorlicensetype::class, 'instructorlicensetypeid', 'instructorlicensetypeid');
    }
}
