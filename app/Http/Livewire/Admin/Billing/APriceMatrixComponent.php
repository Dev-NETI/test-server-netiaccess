<?php

namespace App\Http\Livewire\Admin\Billing;

use Livewire\Component;
use App\Models\tblcompany;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblbillingaccount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class APriceMatrixComponent extends APriceMatrixCoursesSettingsComponents
{
    use ConsoleLog;
    use WithPagination;
    use AuthorizesRequests;
    public $search;
    public $companyid;
    public $clientinfo;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 13);
    }

    public function passSessionData($companyid, $routeName)
    {
        // Set session data
        // dd($companyid);
        Session::forget('companyid');
        Session::put('companyid', $companyid);

        // Redirect to a route or perform any other action here
        return redirect()->route($routeName);
    }

    public function render()
    {
        try {
            $companies = tblcompany::orWhere('company', 'like', '%' . $this->search . '%')
                ->orderBy('company', 'asc')
                ->paginate(5);

            $startNo = ($companies->currentPage() - 1) * $companies->perPage(5) + 1;
            $t_allcompanies = $companies->total();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.billing.a-price-matrix-component',
            [
                't_allcompanies' => $t_allcompanies,
                'companies' => $companies,
                'startNo' => $startNo
            ]
        )->layout('layouts.admin.abase');
    }
}
