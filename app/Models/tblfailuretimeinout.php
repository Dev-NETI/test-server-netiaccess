<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblfailuretimeinout extends Model
{
    use HasFactory;
    protected $table = 'tblfailuretimeinout';
    protected $fillable = [
        'hash', 'user_id', 'dateTime', 'remarks', 'course', 'type', 'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $lastId = $model::orderBy('id', 'DESC')->first();
            $hash_id = $lastId != NULL ? encrypt($lastId->id + 1) : encrypt(1);
            $model->hash = $hash_id;
        });
    }

    public static function updateFailure($data)
    {
        $failure = tblfailuretimeinout::find($data['id']);
        unset($data['id']);
        return $failure->update($data);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function courseDetails()
    {
        return $this->belongsTo(tblcourses::class, 'course', 'courseid');
    }
}
