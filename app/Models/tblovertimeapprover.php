<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblovertimeapprover extends Model
{
    use HasFactory;
    protected $table = 'tblovertimeapprover';
    protected $fillable = [
        'userid',
        'approver'
    ];

    public static function createData(array $data){
        return static::create($data);
    }

    public static function selectApprover($userid){
        return static::where('userid', $userid)->first();
    }

    public static function updateData($userid, array $data){
        return static::where('userid', $userid)->update($data);
    }

    public function instructor(){
        return $this->belongsTo(User::class, 'userid' ,'user_id');
    }

    public function approver(){
        return $this->belongsTo(User::class, 'approver' ,'user_id');
    }
}
