<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class tblpersontonotify extends Authenticatable implements AuthenticatableContract
{
    use AuthenticatableTrait;
    public $table = 'tblpersontonotify';
    public $primaryKey = 'id';

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class,'userid', 'user_id');
    }
}
