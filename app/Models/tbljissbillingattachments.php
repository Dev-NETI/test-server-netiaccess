<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljissbillingattachments extends Model
{
    use HasFactory;
    protected $table = 'tbljissbillingattachments';
    protected $fillable = [
        'jissbillingid', 'attachmentpath', 'filetype'
    ];

    public function jissbilling()
    {
        return $this->belongsTo(tbljissbilling::class, 'jissbillingid');
    }
}
