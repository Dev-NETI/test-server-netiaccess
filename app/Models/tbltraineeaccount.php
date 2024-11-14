<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class tbltraineeaccount  extends Authenticatable implements AuthenticatableContract
{
    use AuthenticatableTrait;
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbltraineeaccount';
    protected $primaryKey = 'traineeid';
    use HasFactory;
    protected $fillable = [
        'login_attempt_count',
        'lockout_timestamp',
        'vessel',
        'f_name',
        'm_name',
        'l_name',
        'suffix',
        'email',
        'dialing_code_id',
        'contact_num',
        'status_id',
        'company_id',
        'fleet_id',
        'vessel',
        'srn_num',
        'tin_num'
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

    public function getFullNameAttribute()
    {
        return $this->l_name . ", " . $this->f_name . " " . $this->m_name . " " . $this->suffix;
    }


    // public function certificate_name()
    // {
    //     $middleInitial = $this->m_name ? strtoupper($this->m_name[0]) . '. ' : '';
    //     $suffix = $this->suffix ? ' ' . $this->suffix : '';

    //     $firstNameParts = explode(' ', $this->f_name);
    //     $formattedFirstName = '';
    //     foreach ($firstNameParts as $part) {
    //         $formattedFirstName .= ucfirst(strtolower($part)) . ' ';
    //     }

    //     $lastNameParts = explode(' ', $this->l_name);
    //     $formattedLastName = '';
    //     foreach ($lastNameParts as $part) {
    //         $formattedLastName .= ucfirst(strtolower($part)) . ' ';
    //     }

    //     $name = rtrim($formattedFirstName) . ' ' . $middleInitial . rtrim($formattedLastName) . $suffix;

    //     return $name;
    // }

    public function vessel()
    {
        return $this->belongsTo(tblvessels::class, 'vessel', 'id');
    }

    public function certificate_name()
    {
        $middleInitial = $this->m_name ? strtoupper($this->m_name[0]) . '. ' : '';
        $suffix = $this->suffix ? ' ' . $this->suffix : '';


        $name = $this->f_name . ' ' . $middleInitial . $this->l_name . $suffix;

        return $name;
    }

    public function rank()
    {
        return $this->belongsTo(tblrank::class, 'rank_id');
    }


    public function company()
    {
        return $this->belongsTo(tblcompany::class, 'company_id');
    }

    public function fleet()
    {
        return $this->belongsTo(tblfleet::class, 'fleet_id');
    }

    public function brgy()
    {
        return $this->belongsTo(refbrgy::class, 'brgyCode', 'brgyCode');
    }

    public function city()
    {
        return $this->belongsTo(refcitymun::class, 'citynumCode', 'citymunCode');
    }

    public function prov()
    {
        return $this->belongsTo(refprovince::class, 'provCode', 'provCode');
    }

    public function reg()
    {
        return $this->belongsTo(refregion::class, 'regCode', 'regCode');
    }

    public function enroled()
    {
        return $this->hasMany(tblenroled::class, 'traineeid', 'traineeid');
    }


    public function gender()
    {
        return $this->belongsTo(tblgender::class, 'genderid');
    }

    public function nationality()
    {
        return $this->belongsTo(tblnationality::class, 'nationalityid', 'nationalityid');
    }

    public function dialing_code()
    {
        return $this->belongsTo(DialingCode::class, 'dialing_code_id', 'id');
    }

    //ASSESSORS
    public function getBirthdayParseAttribute()
    {
        return date('F j, Y', strtotime($this->birthday));
    }

    public function getNameForMealAttribute()
    {
        return $this->l_name . ", " . $this->f_name . " " . $this->m_name . " " . $this->suffix;
    }

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

    public function getLastUpdatedAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getPublicImagePathAttribute()
    {
        return  $this->imagepath === NULL ?
            'data:image/jpeg;base64,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'
            :
            asset('storage/traineepic/' . $this->imagepath);
    }

    public function getAgeAttribute()
    {
        $birthDate = Carbon::parse($this->birthday);
        $now = Carbon::now();
        return $birthDate->diffInYears($now);
    }

    public function getMiddleInitialAttribute()
    {
        return $this->m_name != null ? Str::substr($this->m_name, 0, 1) . "." : '';
    }
}
