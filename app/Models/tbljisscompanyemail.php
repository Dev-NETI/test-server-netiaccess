<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljisscompanyemail extends Model
{
    use HasFactory;
    protected $table = 'tbljisscompanyemail', $fillable = ['jisscompanyid', 'email'];

    public function companyinfo()
    {
        return $this->belongsTo(tbljisscompany::class, 'jisscompanyid');
    }
}
