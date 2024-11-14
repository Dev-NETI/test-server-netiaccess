<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbljisseventlogs extends Model
{
    protected $table = 'tbljisseventlogs';
    protected $fillable = ['fullname', 'logs'];
    use HasFactory;

    public static function default($logs, $fullname){
        tbljisseventlogs::create([
            "logs" => $logs,
            "fullname" => $fullname
        ]);
    }
}
