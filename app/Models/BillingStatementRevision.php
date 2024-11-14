<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingStatementRevision extends Model
{
    use HasFactory;
    protected $fillable = ['schedule_id','company_id','body','sent_by'];

    // mutator
    public function setSentByAttribute($value)
    {
        $this->attributes['sent_by'] = Auth::user()->full_name;
    }
}
