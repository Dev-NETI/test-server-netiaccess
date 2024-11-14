<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class billingattachmenttype extends Model
{
    protected $table = "billingattachmenttype";
    protected $primarykey = "id";
    use HasFactory;

    // relationships
    public function billingattachment()
    {
        return $this->hasMany(billingattachment::class, 'attachmenttypeid');
    }
    
}
