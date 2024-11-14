<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblexportedbillingstatement extends Model
{
    use HasFactory;

    protected $table = 'tblexportedbillingstatement';
    protected $fillable = [
        'scheduleid',
        'companyid',
        'courseid',
        'enroledid',
        'serialnumber',
        'filepath',
        'deletedid',
    ];

    public static function createData(array $data){
        return static::create($data);
    }
}
