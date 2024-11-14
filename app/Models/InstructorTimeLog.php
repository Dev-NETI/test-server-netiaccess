<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InstructorTimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash', 'user_id', 'course_id', 'time_in', 'time_out', 'timestamp_type', 'regular',
        'late', 'undertime', 'overtime', 'status', 'is_active', 'modified_by', 'created_date'
    ];
    public $table = 'instructor_time_logs';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $lastId = $model::orderBy('id', 'DESC')->first();
            $hash_id = $lastId != NULL ? encrypt($lastId->id + 1) : encrypt(1);
            $model->hash = $hash_id;
            $model->modified_by = Auth::user()->full_name;
            // $model->created_date = Carbon::now()->format('Y-m-d');
        });

        static::updating(function ($model) {
            $model->modified_by = Auth::user()->full_name;
        });
    }

    public static function updateOT($id, $data1)
    {
        $data = static::find($id);
        $check = $data->update($data1);

        return $check;
    }

    // relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public static function getLogs($dateFrom, $dateTo)
    {
        return static::where('user_id', Auth::user()->user_id)
            ->where('is_active', 1)
            ->whereBetween('created_date', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'ASC');
    }

    // accessor
    public function getTimeTypeAttribute()
    {
        if ($this->time_in && $this->time_out) {
            $type = "Time in & Time out";
        } else if ($this->time_in) {
            $type = "Time in";
        } else if ($this->time_out) {
            $type = "Time Out";
        } else {
            $type = "";
        }
        return $type;
    }

    public function getLogStatusAttribute()
    {
        switch ($this->status) {
            case 1:
                $status = "Req";
                break;
            case 2:
                $status = "Approve";
                break;
            case 3:
                $status = "Declined";
                break;
            default:
                $status = "";
                break;
        }
        return $status;
    }

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'course_id', 'courseid');
    }

    public function getFormattedCreatedDateAttribute()
    {
        return Carbon::parse($this->attributes['created_date'])->format('l');
    }
}
