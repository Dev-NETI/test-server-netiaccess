<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblbillingstatementcomments extends Model
{
    use HasFactory;
    protected $table = "tblbillingstatementcomments";
    protected $fillable = [
        'scheduleid',
        'comment',
        'creator',
        'isactive',
    ];

    public static function create($data)
    {
        $check = tblbillingstatementcomments::where('scheduleid', $data['scheduleid'])->first();
        if ($check) {
            $check->comment = $data['comment'];
            $check->creator = $data['creator'];
            $check->isactive = 1;
            $check->save();
            return $check;
        } else {
            $tblbillingstatementcomments = new tblbillingstatementcomments();
            $tblbillingstatementcomments->scheduleid = $data['scheduleid'];
            $tblbillingstatementcomments->comment = $data['comment'];
            $tblbillingstatementcomments->creator = $data['creator'];
            $tblbillingstatementcomments->save();
            return $tblbillingstatementcomments;
        }
    }
}
