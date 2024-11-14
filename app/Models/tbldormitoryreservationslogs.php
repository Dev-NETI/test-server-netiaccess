<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class tbldormitoryreservationslogs extends Model
{
    use HasFactory;
    protected $table = 'tbldormitoryreservationslogs';
    protected $fillable = ['logs'];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model){
            $model->created_by = Auth::user()->full_name;
            $model->updated_by = Auth::user()->full_name;
        });

        static::updating(function ($model){
            $model->updated_by = Auth::user()->full_name;
        });
    }

    public function user(){
        return $this->belongsTo(User::class, 'userid', 'user_id');
    }

    // acessor
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('F d, Y H:i:s');
    }
}
