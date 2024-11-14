<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use TCPDF_FONTS;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\tblcompany;
use setasign\Fpdi\Tcpdf\Fpdi;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblcompanycourse;
use App\Models\ClientInformation;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\DB;
use App\Models\tblforeignrate;
use App\Models\tblnyksmcompany;
use Illuminate\Support\Facades\Session;
use App\Traits\GenerateBilingStatementTraits;
use App\Traits\GenerateJISSBillingStatementTraits;

class GenerateBillingStatement2 extends Component
{
    use ConsoleLog;
    use GenerateBilingStatementTraits;
    use GenerateJISSBillingStatementTraits;
    public $companyid;
    public $scheduleid;


    public function generatePDF()
    {
        $this->scheduleid = Session::get('scheduleid');

        $scheddata = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();

        $coursetypeid = $scheddata->course->coursetypeid;

        $traininginfonumchrs = 1;
        $istransferred = session('transferedBilling');
        $this->companyid = Session::get('companyid');

        if ($coursetypeid == 5) {
            $this->jissBilling($this->scheduleid, $this->companyid, $traininginfonumchrs, $istransferred);
        } else {
            $this->standardBilling($istransferred, $traininginfonumchrs, $this->scheduleid, $this->companyid);
        }
    }


    ////////////////////////////////////////////////////////
    ////FUNCTIONS IN GenerateBillingStatementTraits File////
    //////////////////////////////////////////////////////
}
