<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinstructordependents extends Model
{
    protected $table = 'tblinstructordependents';
    protected $primaryKey = 'instructordependentsid';

    public $fillable = [
        'instructorid', 'dependentfullname', 'dependentrelationship', 'dependentbirthdate', 'dependentaddress'
    ];
    use HasFactory;
}
