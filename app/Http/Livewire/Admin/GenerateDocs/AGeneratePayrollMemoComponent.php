<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\Payroll_log;
use App\Models\Payroll_period;
use App\Models\tblcourses;
use App\Models\tblcoursetype;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class AGeneratePayrollMemoComponent extends Component
{
    public $period_start_encrypt;
    public $period_end_encrypt;
    public $period_start;
    public $period_end;
    public $total_net_pay;
    public $count;
    public $memo_num;
    public $hash_id;

    public function getTotalNetPayProperty($period_start_encrypt, $period_end_encrypt)
    {
        $net_pay = 0;
        $categories = tblcoursetype::orderBy('orderbyid', 'ASC')->get();
        $category_ids = $categories->pluck('coursetypeid')->toArray(); // Get an array of category IDs
        $payrolls = Payroll_log::whereIn('tblcourses.coursetypeid', $category_ids)
            ->where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->whereNotNull('course_id')
            ->where('total', '!=', 0)
            ->join('users', 'payroll_logs.user_id', '=', 'users.user_id') // Assuming the foreign key in payroll_logs is user_id
            ->join('tblcourses', 'tblcourses.courseid', '=', 'payroll_logs.course_id')
            ->join('tblcoursetype', 'tblcourses.coursetypeid', '=', 'tblcoursetype.coursetypeid') // Join tblcoursetype
            ->whereNotIn('tblcourses.coursetypeid', [11])
            ->orderBy('tblcoursetype.orderbyid', 'ASC') // Order by orderbyid
            ->orderBy('users.l_name', 'ASC') // Secondary order by user's last name
            ->get();
        foreach ($payrolls as $data) {
            $net_pay += $data->subtotal; // use -> instead of []
        }
        return $net_pay;
    }

    public function getTotalNonTeachingNetPayProperty()
    {
        $net_pay = 0;
        $payrolls = Payroll_log::where('tblcourses.coursetypeid', 11)
            ->where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->whereNotNull('course_id')
            ->where('total', '!=', 0)
            ->join('users', 'payroll_logs.user_id', '=', 'users.user_id') // Assuming the foreign key in payroll_logs is user_id
            ->join('tblcourses', 'tblcourses.courseid', '=', 'payroll_logs.course_id')
            ->join('tblcoursetype', 'tblcourses.coursetypeid', '=', 'tblcoursetype.coursetypeid') // Join tblcoursetype
            ->orderBy('tblcoursetype.orderbyid', 'ASC') // Order by orderbyid
            ->orderBy('users.l_name', 'ASC') // Secondary order by user's last name
            ->get();
        foreach ($payrolls as $data) {
            $net_pay += $data->subtotal; // use -> instead of []
        }
        return $net_pay;
    }

    public function generatePdf($hash_id)
    {
        $this->hash_id = $hash_id;
        $period = Payroll_period::where('hash_id', $hash_id)->first();
        $this->period_start = $period->period_start;
        $this->period_end = $period->period_end;


        $memo_num = session('memo_num');
        $memo_note = session('memo_note');


        // $period = Payroll_period::where('period_start',$period_start)->where('period_end', $period_end)->first();
        // dd($period);
        $time = Carbon::now('Asia/Manila');
        $user = User::find(Auth::user()->id);

        //GROUP BY THEIR CAREGORY
        $categories = tblcoursetype::orderBy('orderbyid', 'ASC')->get();
        $category_ids = $categories->pluck('coursetypeid')->toArray(); // Get an array of category IDs

        $payrolls = Payroll_log::whereIn('tblcourses.coursetypeid', $category_ids)
            ->where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->whereNotNull('course_id')
            ->where('total', '!=', 0)
            ->join('users', 'payroll_logs.user_id', '=', 'users.user_id') // Assuming the foreign key in payroll_logs is user_id
            ->join('tblcourses', 'tblcourses.courseid', '=', 'payroll_logs.course_id')
            ->join('tblcoursetype', 'tblcourses.coursetypeid', '=', 'tblcoursetype.coursetypeid') // Join tblcoursetype
            ->whereNotIn('tblcourses.coursetypeid', [11])
            ->orderBy('tblcoursetype.orderbyid', 'ASC') // Order by orderbyid
            ->orderBy('users.l_name', 'ASC') // Secondary order by user's last name
            ->get();


        $count_per_category = $payrolls->groupBy('category_id')->map(function ($group) {
            return [
                'count' => $group->count(),
                'subtotal' => $group->sum('subtotal')
            ];
        });


        // dd($count_per_category[1]['count']);

        $payrolls_by_category = $payrolls->groupBy('category_id');

        // dd($payrolls_by_category = $payrolls->groupBy('category_id'));

        //TOTAL NET PAY
        $total_net_pay = SELF::getTotalNetPayProperty($this->period_start, $this->period_end);


        //COUNT THE CONDUCTED COURSE
        $count = $payrolls->count();

        // dd($total_net_pay, $count);
        $data = [
            'payrolls' => $payrolls,
            'total_net_pay' => $total_net_pay,
            'time' => $time->format('F j, Y g:i A'),
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'payrolls_by_category' => $payrolls_by_category,
            'categories' => $categories,
            'user' => $user,
            'count' => $count,
            'count_per_category' => $count_per_category,
            'memo_num' => $memo_num,
            'memo_note' => $memo_note,
            'non_teaching' => false
        ];

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-payroll-memo-component', $data);
        return $pdf->stream();
    }

    public function generateNonTeachingPdf($hash_id)
    {
        $this->hash_id = $hash_id;
        $period = Payroll_period::where('hash_id', $hash_id)->first();
        $this->period_start = $period->period_start;
        $this->period_end = $period->period_end;


        $memo_num = session('non_memo_num');
        $memo_note = session('non_memo_note');

        // $period = Payroll_period::where('period_start',$period_start)->where('period_end', $period_end)->first();
        // dd($period);
        $time = Carbon::now('Asia/Manila');
        $user = User::find(Auth::user()->id);

        //GROUP BY THEIR CAREGORY
        $categories = tblcoursetype::orderBy('orderbyid', 'ASC')->get();

        $payrolls = Payroll_log::where('tblcourses.coursetypeid', 11)
            ->where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->whereNotNull('course_id')
            ->where('total', '!=', 0)
            ->join('users', 'payroll_logs.user_id', '=', 'users.user_id') // Assuming the foreign key in payroll_logs is user_id
            ->join('tblcourses', 'tblcourses.courseid', '=', 'payroll_logs.course_id')
            ->join('tblcoursetype', 'tblcourses.coursetypeid', '=', 'tblcoursetype.coursetypeid') // Join tblcoursetype
            ->orderBy('tblcoursetype.orderbyid', 'ASC') // Order by orderbyid
            ->orderBy('users.l_name', 'ASC') // Secondary order by user's last name
            ->get();


        $count_per_category = $payrolls->groupBy('category_id')->map(function ($group) {
            return [
                'count' => $group->count(),
                'subtotal' => $group->sum('subtotal')
            ];
        });

        // dd($count_per_category[1]['count']);

        $payrolls_by_category = $payrolls->groupBy('category_id');

        // dd($payrolls_by_category = $payrolls->groupBy('category_id'));

        //TOTAL NET PAY
        $total_net_pay = SELF::getTotalNonTeachingNetPayProperty();


        //COUNT THE CONDUCTED COURSE
        $count = $payrolls->count();

        // dd($total_net_pay, $count);
        $data = [
            'payrolls' => $payrolls,
            'total_net_pay' => $total_net_pay,
            'time' => $time->format('F j, Y g:i A'),
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'payrolls_by_category' => $payrolls_by_category,
            'categories' => $categories,
            'user' => $user,
            'count' => $count,
            'count_per_category' => $count_per_category,
            'memo_num' => $memo_num,
            'memo_note' => $memo_note,
            'non_teaching' => true
        ];

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-payroll-memo-component', $data);
        return $pdf->stream();
    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-payroll-memo-component');
    }
}
