<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblnyksmcompany extends Model
{
    use HasFactory;
    protected $table = 'tblnyksmcompany';
    protected $fillable = ['companyid'];

    public function companyinfo()
    {
        return $this->belongsTo(tblcompany::class, 'companyid');
    }

    public static function getcompanyid()
    {
        return static::all()->pluck('companyid')->toArray();
    }

    public static function getcompanywoNYKLINE()
    {
        return static::all()->where('companyid', '!=', 89)->pluck('companyid')->toArray();
    }
}
