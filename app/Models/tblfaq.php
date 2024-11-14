<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblfaq extends Model
{
    protected $table = 'tblfaq';
    protected $primaryKey = 'faqid';
    protected $fillable=['question','answer','statusid','deletedid'];
    use HasFactory;
}
