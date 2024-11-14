<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'f_name',
        'm_name',
        'l_name',
        'email',
        'password_tip',
        'password',
        'u_type',
        'is_active',
        'contact_num',
        'hash_id',
        'dep_type',
        'user_id',
        'suffix',
        'birthday',
        'birthplace',
        'jisscompany_id',
        'regCode',
        'provCode',
        'citynumCode',
        'brgyCode',
        'street',
        'postal',
        'imagepath',
        'company_id',
        'fleet_id',
        'route'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function formal_name()
    {
        $middleInitial = $this->m_name ? $this->m_name[0] : '';
        $suffix = $this->suffix ? ' ' . $this->suffix : '';

        $name = $this->l_name . ', ' . $this->f_name . ' ' . $middleInitial . $suffix;

        if ($this->m_name) {
            $name .= '.';
        }

        return $name;
    }

    public function schedule()
    {
        return $this->hasMany(tblcourseschedule::class, 'instructorid', 'user_id');
    }

    public function getInstructorNameAttribute()
    {
        if ($this->user_id != 93) {
            $middleInitial = $this->m_name ? $this->m_name[0] : '';
            $suffix = $this->suffix ? ' ' . $this->suffix : '';

            $name = $this->l_name . ', ' . $this->f_name . ' ' . $middleInitial . $suffix;

            if ($this->m_name) {
                $name .= '.';
            }
            return $name;
        } else {
            $name = '';
            return $name;
        }
    }

    public function instructor_time_log()
    {
        return $this->hasMany(InstructorTimeLog::class, 'user_id', 'user_id');
    }

    public function dialing_code()
    {
        return $this->belongsTo(DialingCode::class, 'dialing_code_id', 'id');
    }

    public function adminroles()
    {
        return $this->hasMany(adminroles::class, 'user_id', 'user_id');
    }

    public function rank()
    {
        return $this->belongsTo(tblrank::class, 'rank_id');
    }

    // Trainee model
    public function enrollments()
    {
        return $this->hasMany(tblenroled::class, 'traineeid', 'traineeid');
    }

    public function instructor()
    {
        return $this->belongsTo(tblinstructor::class, 'user_id', 'userid');
    }


    public function instructorOne()
    {
        return $this->hasOne(tblinstructor::class, 'userid');
    }

    public function region()
    {
        return $this->belongsTo(refregion::class, 'regCode', 'regCode');
    }

    public function province()
    {
        return $this->belongsTo(refprovince::class, 'provCode', 'provCode');
    }

    public function citymun()
    {
        return $this->belongsTo(refcitymun::class, 'citynumCode', 'citymunCode');
    }

    public function barangay()
    {
        return $this->belongsTo(refbrgy::class, 'brgyCode', 'brgyCode');
    }

    public function attachment()
    {
        return $this->hasMany(tblinstructorattachment::class, 'userid');
    }

    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'company_id', 'companyid');
    }

    //encapsulations
    //ASSESSORS


    public function getPdeRequestRouteAttribute()
    {
        switch ($this->u_type) {
            case 5:
                $route = 'nte.requestpde';
                break;
            case 3:
                $route = 'c.requestpde';
                break;
            default:
                $route = 'a.requestpde';
                break;
        }
        return $route;
    }

    public function getPdeStatusRouteAttribute()
    {
        switch ($this->u_type) {
            case 5:
                $route = 'nte.pdestatus';
                break;
            case 3:
                $route = 'c.pdestatus';
                break;
            default:
                $route = 'a.pdestatus';
                break;
        }
        return $route;
    }

    public function getUploadAttachmentRouteAttribute()
    {
        return $this->u_type === 1 ? 'a.billing-viewtrainees' : 'c.client-billing-view-trainees';
    }

    public function getAttendanceRouteAttribute()
    {
        return $this->u_type === 1 ? 'billing.viewattendance' : 'c.viewattendance';
    }

    public function getFullNameAttribute()
    {
        return $this->f_name . " " . $this->m_name . " " . $this->l_name;
    }

    public function getBillingStatementRouteAttribute()
    {
        return $this->u_type === 1 ? 'a.billing-statement2' : 'c.billing-statement2';
    }

    public function getBillingDashboardRouteAttribute()
    {
        return $this->u_type != 3 ? 'a.billing-monitoring' : 'c.client-billing-monitoring';
    }

    //mutator

    public function getMobileNumberAttribute()
    {
        $dialing_code = DialingCode::where('id', $this->dialing_code_id)->first();
        if ($dialing_code === null) {
            $contact_num = $this->contact_num;
        } else {
            $contact_num = $dialing_code->dialing_code . $this->contact_num;
        }
        return $contact_num;
    }
}
