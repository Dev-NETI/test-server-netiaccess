<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingstatus;
use App\Models\tblenroled;
use App\Models\tblnyksmcompany;
use App\Models\tbltraineeaccount;
use App\Traits\BillingModuleTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ASearchBillingComponent extends Component
{
    use WithPagination;
    use BillingModuleTrait;
    public $searchBar;
    public $selectedBoards;

    public function passSessionData($scheduleid, $companyid, $status)
    {
        Session::put('billingstatusid', $status);
        Session::put('scheduleid', $scheduleid);
        Session::put('companyid', $companyid);

        return redirect()->route('a.billing-viewtrainees');
    }

    public function listQuery($currentWeek = null, $search = null)
    {
        $status = [3, 4, 5, 6, 7, 8, 9, 10];
        $nykComp = tblnyksmcompany::getcompanyid();
        $avoidcomp = [...$nykComp, 1, 3];
        $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->join('tblcompanycourse', 'tblcompany.companyid', '=', 'tblcompanycourse.companyid')
            ->where(function ($query) use ($status, $search, $currentWeek, $avoidcomp) {
                $query->whereIn('tblenroled.billingstatusid', $status)
                    ->where('tblenroled.dropid', '!=', 1)
                    ->where('tblenroled.deletedid', '!=', 1)
                    ->where('tblenroled.nabillnaid', '!=', 1);

                if (!$status >= 6) {
                    $query->where('tblenroled.IsRemedial', '!=', 1);
                }

                $query->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblenroled.attendance_status', '!=', 1)
                    ->whereNotIn('tbltraineeaccount.company_id', $avoidcomp)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-01')
                    ->whereNotIn('tblcourses.coursetypeid', [7, 5]);

                if ($search !== null) {
                    $query->where(function ($query) use ($search) {
                        // Search across multiple fields
                        $query->where('tblcompany.company', 'LIKE', '%' . $search . '%') // Search in company name
                            ->orWhere('tblcourses.coursecode', 'LIKE', '%' . $search . '%') // Search in course code
                            ->orWhere('tblcourses.coursename', 'LIKE', '%' . $search . '%') // Search in course name
                            ->orWhere('tblenroled.billingserialnumber', 'LIKE', '%' . $search . '%'); // Search in serial number
                    });
                }
                if ($currentWeek !== null) {
                    $query->where('tblcourseschedule.batchno', $currentWeek);
                }
            });


        //Get NYKSM schedule if there is trainee
        $valueToRemove = 89;

        $array = array_filter($nykComp, function ($item) use ($valueToRemove) {
            return $item !== $valueToRemove;
        });

        // Reindex array
        $nykCompremoveNYKLINE = array_values($array);

        $query->orWhere(function ($query) use ($status, $currentWeek, $search, $nykCompremoveNYKLINE) {
            $query->whereIn('tblenroled.billingstatusid', $status)
                ->where('tblenroled.deletedid', '!=', 1)
                ->where('tblenroled.dropid', '!=', 1)
                ->where('tblenroled.IsRemedial', '!=', 1)
                ->where('tblenroled.nabillnaid', '!=', 1)
                ->where('tblenroled.reservationstatusid', '!=', 4)
                ->where('tblenroled.attendance_status', '!=', 1)
                ->whereIn('tbltraineeaccount.company_id', $nykCompremoveNYKLINE)
                ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');

            if ($search !== null) {
                $query->where(function ($query) use ($search) {
                    // Search across multiple fields
                    $query->where('tblcompany.company', 'LIKE', '%' . $search . '%') // Search in company name
                        ->orWhere('tblcourses.coursecode', 'LIKE', '%' . $search . '%') // Search in course code
                        ->orWhere('tblcourses.coursename', 'LIKE', '%' . $search . '%') // Search in course name
                        ->orWhere('tblenroled.billingserialnumber', 'LIKE', '%' . $search . '%'); // Search in serial number
                });
            }

            if ($currentWeek !== null) {
                $query->where('tblcourseschedule.batchno', $currentWeek);
            }
        });

        //Get NYKLINE schedule if there is japanese trainee
        $query->orWhere(function ($query) use ($status, $search, $currentWeek) {
            $query->whereIn('tblenroled.billingstatusid', $status)
                ->where('tblenroled.deletedid', '!=', 1)
                ->where('tblenroled.dropid', '!=', 1)
                ->where('tblenroled.IsRemedial', '!=', 1)
                ->where('tblenroled.nabillnaid', '!=', 1)
                ->where('tblenroled.reservationstatusid', '!=', 4)
                ->where('tblenroled.attendance_status', '!=', 1)
                ->where('tbltraineeaccount.nationalityid', 51)
                ->where('tbltraineeaccount.company_id', 89)
                ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');
            if ($search !== null) {
                $query->where(function ($query) use ($search) {
                    // Search across multiple fields
                    $query->where('tblcompany.company', 'LIKE', '%' . $search . '%') // Search in company name
                        ->orWhere('tblcourses.coursecode', 'LIKE', '%' . $search . '%') // Search in course code
                        ->orWhere('tblcourses.coursename', 'LIKE', '%' . $search . '%') // Search in course name
                        ->orWhere('tblenroled.billingserialnumber', 'LIKE', '%' . $search . '%'); // Search in serial number
                });
            }
            if ($currentWeek !== null) {
                $query->where('tblcourseschedule.batchno', $currentWeek);
            }
        });

        //Get IOTC schedule if there is trainee
        $query->orWhere(function ($query) use ($status, $search, $currentWeek) {
            $query->whereIn('tblenroled.billingstatusid', $status)
                ->where('tblenroled.deletedid', '!=', 1)
                ->where('tblenroled.dropid', '!=', 1)
                ->where('tblenroled.IsRemedial', '!=', 1)
                ->where('tblenroled.nabillnaid', '!=', 1)
                ->where('tblenroled.reservationstatusid', '!=', 4)
                ->where('tblenroled.attendance_status', '!=', 1)
                ->where('tbltraineeaccount.company_id', 3)
                ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');
            if ($search !== null) {
                $query->where(function ($query) use ($search) {
                    // Search across multiple fields
                    $query->where('tblcompany.company', 'LIKE', '%' . $search . '%') // Search in company name
                        ->orWhere('tblcourses.coursecode', 'LIKE', '%' . $search . '%') // Search in course code
                        ->orWhere('tblcourses.coursename', 'LIKE', '%' . $search . '%') // Search in course name
                        ->orWhere('tblenroled.billingserialnumber', 'LIKE', '%' . $search . '%'); // Search in serial number
                });
            }
            if ($currentWeek !== null) {
                $query->where('tblcourseschedule.batchno', $currentWeek);
            }
        });

        //Get NSMI schedule if there is trainee
        $query->orWhere(function ($query) use ($status, $search, $currentWeek) {
            $query->whereIn('tblenroled.billingstatusid', $status)
                ->where('tblenroled.deletedid', '!=', 1)
                ->where('tblenroled.dropid', '!=', 1)
                ->where('tblenroled.nabillnaid', '!=', 1)
                ->where('tblenroled.IsRemedial', '!=', 1)
                ->where('tblenroled.reservationstatusid', '!=', 4)
                ->where('tblenroled.attendance_status', '!=', 1)
                ->where('tbltraineeaccount.company_id', '=', 1)
                ->where('tblcourseschedule.startdateformat', '>', '2024-03-11')
                ->whereIn('tblcourseschedule.courseid', [91, 92, 88]);
            if ($search !== null) {
                $query->where(function ($query) use ($search) {
                    // Search across multiple fields
                    $query->where('tblcompany.company', 'LIKE', '%' . $search . '%') // Search in company name
                        ->orWhere('tblcourses.coursecode', 'LIKE', '%' . $search . '%') // Search in course code
                        ->orWhere('tblcourses.coursename', 'LIKE', '%' . $search . '%') // Search in course name
                        ->orWhere('tblenroled.billingserialnumber', 'LIKE', '%' . $search . '%'); // Search in serial number
                });
            }
            if ($currentWeek !== null) {
                $query->where('tblcourseschedule.batchno', $currentWeek);
            }
        });

        $query->select(
            'tblcourses.coursecode',
            'tblcourses.coursename',
            'tblcourseschedule.scheduleid',
            'tblcourseschedule.batchno',
            'tblcourseschedule.startdateformat',
            'tblcourseschedule.enddateformat',
            'tblcourseschedule.deletedid',
            'tblcompany.company',
            'tblcompany.companyid',
            'tblcompany.billing_statement_format',
            'tbltraineeaccount.nationalityid',
            'tblenroled.billing_modified_by',
            'tblenroled.datebilled',
            'tblenroled.billingstatusid',
            'tblenroled.datebilled',
            'tblenroled.companyid AS enroledcompanyid',
            'tblenroled.nabillnaid',
            'tblenroled.billing_updated_at'
        )
            ->distinct(['tblcourseschedule.scheduleid', 'tblcompany.company'])
            ->orderBy('tblcourseschedule.startdateformat', 'DESC');
        return $query;
    }

    public function render()
    {
        $data = $this->listQuery(null, $this->searchBar);
        $data  = $this->mergeCompanyByShed($data->get());

        if ($this->selectedBoards) {
            $selectedBoards = $this->selectedBoards;
            $data = array_filter($data, function ($item) use ($selectedBoards) {
                return $item['billingstatusid'] == $selectedBoards;
            });
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Slice the array to get the items to display for the current page
        $currentItems = array_slice($data, ($currentPage - 1) * 10, 10);

        // Create a LengthAwarePaginator instance
        $billinglist =  new LengthAwarePaginator(
            $currentItems,
            count($data), // Total items
            10,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        $billingBoards = tblbillingstatus::whereNotIn('id', [1, 2])->get();


        return view('livewire.admin.billing.a-search-billing-component', compact('billinglist', 'billingBoards'))->layout('layouts.admin.abase');
    }

    private function oldbillinglist()
    {
        // $billinglist = $data->paginate(10);
        // $query = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        //     ->join('tblcourses', 'tblenroled.courseid', '=', 'tblcourses.courseid')
        //     ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
        //     ->join('tblrank', 'tbltraineeaccount.rank_id', '=', 'tblrank.rankid')
        //     ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
        //     ->where('tblenroled.billingstatusid', '>', 1)
        //     ->where('tblenroled.deletedid', 0)
        //     ->where('tblenroled.deletedid', 0)
        //     ->where('tblenroled.nabillnaid', 0)
        //     ->where('tblenroled.billing_modified_by', '!=', NULL);

        // $query->select(
        //     'tblenroled.billingstatusid',
        //     'tblenroled.datebilled',
        //     'tblenroled.billing_modified_by',
        //     'tblcourses.coursecode',
        //     'tblcourses.coursename',
        //     'tblcompany.company',
        //     'tblcourseschedule.batchno',
        //     'tblcourseschedule.scheduleid',
        //     'tblcompany.companyid'
        // );
        // $query->distinct(['tblcourseschedule.scheduleid', 'tblcompany.companyid'])
        //     ->orderBy('tblenroled.datebilled', 'desc');

        // $data = $query->get();
        // $nykComps = tblnyksmcompany::getcompanyid();

        // if ($this->searchBar != '') {
        //     $searchBar = $this->searchBar;
        //     // Process the results by adding the billing serial number
        //     $processedResults = $data->map(function ($item) {
        //         $item->billingserialnumber = $this->getSerialNumber($item->scheduleid, $item->companyid);
        //         return $item;
        //     });

        //     // Filter the results based on the search bar input
        //     $searchResults = $processedResults->filter(function ($item) use ($searchBar) {
        //         return stripos($item->company, $searchBar) !== false ||
        //             stripos($item->coursecode, $searchBar) !== false ||
        //             stripos($item->coursename, $searchBar) !== false ||
        //             stripos($item->batchno, $searchBar) !== false ||
        //             stripos($item->billingserialnumber, $searchBar) !== false;
        //     });

        //     // Ensure search results are unique
        //     $uniqueResults = $searchResults->unique(function ($item) {
        //         return $item->scheduleid . '_' . $item->companyid;
        //     });

        //     // Get the current page for pagination
        //     $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //     // Define how many items we want to be visible in each page
        //     $perPage = 10;

        //     // Slice the collection to get the items to display in current page
        //     $currentItems = $uniqueResults->slice(($currentPage - 1) * $perPage, $perPage)->all();

        //     // Create our paginator and pass it to the view
        //     $paginatedItems = new LengthAwarePaginator($currentItems, $uniqueResults->count(), $perPage);

        //     // Set URL path for generated links
        //     $paginatedItems->setPath(request()->url());



        //     $billinglist = $paginatedItems;
        // } else {
        //     $billinglist = $query->paginate(10);
        // }
    }
}
