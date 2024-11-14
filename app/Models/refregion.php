<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class refregion extends Model
{
    protected $table = 'refregion';
    protected $primaryKey = 'id';

    use HasFactory;

    public function trainee()
    {
        return $this->hasMany(tbltraineeaccount::class, 'regCode', 'regCode');
    }
}
