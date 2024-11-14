<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblemailinquiry extends Model
{
    protected $table = 'tblemailinquiry';
    protected $primaryKey = 'emailinquiryid'; 
    protected $fillable=['hash_id','name','email','mobile','telephone','address','company',
    'inquiry_text','is_answered','answered_by','date_answered','date_deleted','deleted_by','is_deleted','inquirytypeid','faqid','faqanswer'];
    use HasFactory;

    public function tblinquirytype()
    {
        return $this->belongsTo(tblinquirytype::class, 'inquirytypeid');
    }

    public function inquiryType()
    {
        return $this->belongsTo(tblinquirytype::class, 'inquirytypeid');
    }


}
