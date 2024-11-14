<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinquirytype extends Model
{
    protected $table = 'tblinquirytype';
    protected $primaryKey = 'inquirytypeid';
    
    use HasFactory;

    public function inquirytype()
    {
        return $this->belongsTo(tblinquirytype::class,'inquirytypeid');
    }
    
}
