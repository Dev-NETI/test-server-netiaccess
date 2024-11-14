<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UpdateTraineeInfoLogs extends Model
{
    use HasFactory;
    protected $table = 'updatetraineeinfologs';
    protected $fillable = ['logs', 'modified_by'];

    public static function StoreLogs($logs)
    {
        $modifier = Auth::user()->f_name . ' ' . Auth::user()->l_name;
        static::create([
            'logs' => $logs,
            'modified_by' => $modifier,
        ]);
    }
}
