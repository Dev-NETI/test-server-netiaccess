<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tblenroled extends Model
{
    protected $table = 'tblenroled';
    protected $primaryKey = 'enroledid';
    protected $fillable = [
        'pendingid',
        'deletedid',
        'busid',
        'companyid',
        'discount',
        'billingserialnumber',
        'reservationstatusid',
        'dormid',
        'istransferedbilling',
        'attendance_status',
        'nabillnaid',
        'is_SignatureAttached',
        'billingstatusid',
        'billing_updated_at',
        'billing_modified_by',
        'wifi_username',
        'wifi_password',
        'wifi_expiration',
    ];
    use HasFactory;

    public function enroledcompany()
    {
        return $this->belongsTo(tblcompany::class, 'companyid');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->enrolledby = Auth::guard('trainee')->check() ? Auth::guard('trainee')->user()->name_for_meal : Auth::user()->full_name;
        });

        static::created(function ($model) {
            $model->update([
                'wifi_username' => Str::replace(' ', '', $model->trainee->f_name) . "." . Str::replace(' ', '', $model->trainee->l_name),
                'wifi_password' => $model->encryptTo8Chars($model->enroledid),
                'wifi_expiration' => $model->schedule->enddateformat,
            ]);
        });
    }

    public function schedule()
    {
        return $this->belongsTo(tblcourseschedule::class, 'scheduleid');
    }

    public function course()
    {
        return $this->belongsTo(tblcourses::class, 'courseid');
    }

    public function trainee()
    {
        return $this->belongsTo(tbltraineeaccount::class, 'traineeid', 'traineeid');
    }

    public function fleet()
    {
        return $this->belongsTo(tblfleet::class, 'fleetid', 'fleetid');
    }

    public function payment()
    {
        return $this->belongsTo(tblpaymentmode::class, 'paymentmodeid');
    }

    public function dorm()
    {
        return $this->belongsTo(tbldorm::class, 'dormid');
    }

    public function attendance()
    {
        return $this->hasMany(tblscheduleattendance::class);
    }

    public function certificate()
    {
        return $this->belongsTo(tblcertificatehistory::class, 'traineeid', 'traineeid');
    }

    public function certificate_number()
    {
        return $this->belongsTo(tblcertificatehistory::class, 'traineeid', 'traineeid')
            ->where('enroledid', $this->enroledid)
            ->first();
    }

    public function cln()
    {
        return $this->belongsTo(tblclntype::class, 'cln_id');
    }

    public function dormitory()
    {
        return $this->belongsTo(tbldormitoryreservation::class, 'enroledid', 'enroledid');
    }

    public function reservationstatus()
    {
        return $this->belongsTo(tblreservationstatus::class, 'reservationstatusid', 'id');
    }

    public function bus()
    {
        return $this->belongsTo(tblbusmode::class, 'busid');
    }

    public function tshirt()
    {
        return $this->belongsTo(tbltshirt::class, 'tshirtid');
    }

    public function old_schedule()
    {
        return $this->belongsTo(tblremedial::class, 'enroledid', 'enroledid');
    }

    public function tper_rating()
    {
        return $this->hasMany(Tper_evaluation_rating::class, 'enroled_id', 'enroledid');
    }

    public function mealmonitoring()
    {
        return $this->hasMany(tblmealmonitoring::class, 'enroledid', 'enroledid');
    }

    public function busmonitoring()
    {
        return $this->hasMany(tblbusmonitoring::class, 'enroledid', 'enroledid');
    }

    public function certificate_history()
    {
        return $this->belongsTo(tblcertificatehistory::class, 'enroledid', 'enroledid');
    }

    public function transfer()
    {
        return $this->belongsTo(tbltransferbilling::class, 'enroledid', 'enroledid');
    }

    // ASSESSOR
    public function getDownloadDormRegFormAttribute()
    {
        return $this->dormid != 1 ?
            (
                Auth::user()->u_type === 1 ?
                '<a href="' . route('a.registration-form', ['enrollment_id' => $this->enroledid]) . '" class="h5" title="Download Registration Form">
                <span class="bi bi-download"></span>
            </a>'
                :
                '<a href="' . route('c.registration-form', ['enrollment_id' => $this->enroledid]) . '" class="h5" title="Download Registration Form">
                <span class="bi bi-download"></span>
            </a>'
            )
            :
            '';
    }

    public function droplogs()
    {
        return $this->belongsTo(tblbillingdrop::class, 'enroledid', 'enroledid');
    }

    public function getEnrollmentStatusAttribute()
    {
        if ($this->pendingid === 0 && $this->deletedid === 0) {
            $badge = '<span class="badge bg-success">Enrolled</span>';
        } else if ($this->pendingid === 0 && $this->deletedid === 1) {
            $badge = '<span class="badge bg-danger">Drop</span>';
        } else {
            $badge = '<span class="badge bg-warning">Pending</span>';
        }
        return $badge;
    }

    public function getTemplateAttribute()
    {
        if ($this->certificate_template_id == 1) {
            return 'ABOVE';
        } elseif ($this->certificate_template_id == 0) {
            return 'BELOW';
        }
    }


    //for WiFi
    public function encryptTo8Chars($input)
    {
        $hash = hash('sha256', $input);
        $base62 = self::base62Encode(hexdec(substr($hash, 0, 15)));
        return substr($base62, 0, 8);
    }

    public function base62Encode($number)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $encoded = '';

        while ($number > 0) {
            $encoded = $characters[$number % $base] . $encoded;
            $number = floor($number / $base);
        }

        return $encoded;
    }
}
