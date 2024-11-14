<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class tblinstructorattachment extends Model
{
    protected $table = 'tblinstructorattachment';
    protected $primaryKey = 'id';
    use HasFactory;

    public function attachmenttype(){
        return $this->belongsTo(tblinstructorattachmenttype::class, 'attachmenttypeid');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'userid', 'user_id');
    }

}
