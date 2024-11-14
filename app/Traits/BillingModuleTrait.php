<?php

namespace App\Traits;

use App\Models\tblenroled;
use App\Models\tblnyksmcompany;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Gate;

trait BillingModuleTrait
{
    public function countTrainee($scheduleId, $companyId)
    {
        $nykmcompanyids = tblnyksmcompany::getcompanyid();
        $serialNumber = tblenroled::with('trainee.vessel')
            ->select('billingserialnumber')
            ->whereIn('scheduleid', $scheduleId)
            ->where([
                ['dropid', '!=', 1],
                ['deletedid', '!=', 1],
                ['nabillnaid', '!=', 1],
                ['istransferedbilling', '!=', 1],
                ['reservationstatusid', '!=', 4]
            ]);

        if (in_array($companyId, $nykmcompanyids)) {
            $serialNumber->where('attendance_status', 0);
        }

        $serialNumber->whereHas('trainee', function ($query) use ($companyId, $nykmcompanyids) {
            if (in_array($companyId, $nykmcompanyids)) {
                $query->whereIn('company_id', $nykmcompanyids)->where('nationalityid', '!=', 51);
            } elseif ($companyId == 89) {
                $query->where('company_id', 89)->where('nationalityid', 51);
            } else {
                $query->where('company_id', $companyId);
            }
        });

        // Get Remedial Trainees
        $serialNumber2 = tblenroled::with('trainee.vessel')
            ->select('billingserialnumber')
            ->where([
                ['IsRemedial', 1],
                ['deletedid', '!=', 1],
                ['reservationstatusid', '!=', 4],
                ['istransferedbilling', '!=', 1],
                ['nabillnaid', '!=', 1],
                ['dropid', '!=', 1]
            ]);
        array_push($nykmcompanyids, 1);
        if (in_array($companyId, $nykmcompanyids)) {
            $serialNumber2->where('attendance_status', 0);
        }

        $nykmcompanyids = tblnyksmcompany::getcompanyid();
        $serialNumber2->whereIn('remedial_sched', $scheduleId)
            ->whereHas('trainee', function ($query) use ($companyId, $nykmcompanyids) {
                if (in_array($companyId, $nykmcompanyids)) {
                    $query->whereIn('company_id', $nykmcompanyids)->where('nationalityid', '!=', 51);
                } elseif ($companyId == 89) {
                    $query->where('company_id', 89)->where('nationalityid', 51);
                } else {
                    $query->where('company_id', $companyId);
                }
            });

        $countTrainee = $serialNumber->count() + $serialNumber2->count();

        return $countTrainee;
    }

    public function ScheduleListQueryforNotification($currentWeek = null, $billingStatusId, $search = null)
    {
        $nykComp = tblnyksmcompany::getcompanyid();
        $avoidcomp = [...$nykComp, 1, 3];
        $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->join('tblcompanycourse', 'tblcompany.companyid', '=', 'tblcompanycourse.companyid')
            ->where(function ($query) use ($billingStatusId, $search, $currentWeek, $avoidcomp) {
                $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                    ->where('tblenroled.dropid', '!=', 1)
                    ->where('tblenroled.deletedid', '!=', 1)
                    ->where('tblenroled.istransferedbilling', '!=', 1)
                    ->where('tblenroled.nabillnaid', '!=', 1);

                if (!$billingStatusId >= 6) {
                    $query->where('tblenroled.IsRemedial', '!=', 1);
                }

                $query->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblenroled.attendance_status', '!=', 1)
                    ->whereNotIn('tbltraineeaccount.company_id', $avoidcomp)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-01');
                $query->whereIn('tblcourses.coursetypeid', [7]);

                $query->where('tblcompany.company', 'LIKE', '%' . $search . '%');
                if ($currentWeek !== null) {
                    $query->where('tblcourseschedule.batchno', $currentWeek);
                }
            });


        //Get NYKSM schedule if there is trainee
        if ($search == null) {
            $valueToRemove = 89;

            $array = array_filter($nykComp, function ($item) use ($valueToRemove) {
                return $item !== $valueToRemove;
            });

            // Reindex array
            $nykCompremoveNYKLINE = array_values($array);

            $query->orWhere(function ($query) use ($billingStatusId, $currentWeek, $nykCompremoveNYKLINE) {
                $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                    ->where('tblenroled.deletedid', '!=', 1)
                    ->where('tblenroled.dropid', '!=', 1)
                    ->where('tblenroled.IsRemedial', '!=', 1)
                    ->where('tblenroled.istransferedbilling', '!=', 1)
                    ->where('tblenroled.nabillnaid', '!=', 1)
                    ->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblenroled.attendance_status', '!=', 1)
                    ->whereIn('tbltraineeaccount.company_id', $nykCompremoveNYKLINE)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');

                if ($currentWeek !== null) {
                    $query->where('tblcourseschedule.batchno', $currentWeek);
                }
            });
        }

        //Get NYKLINE schedule if there is japanese trainee
        if ($search == null || in_array(strtolower($search), ['nyk', 'nykline', 'line', 'nykline'])) {
            $query->orWhere(function ($query) use ($billingStatusId, $currentWeek) {
                $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                    ->where('tblenroled.deletedid', '!=', 1)
                    ->where('tblenroled.dropid', '!=', 1)
                    ->where('tblenroled.IsRemedial', '!=', 1)
                    ->where('tblenroled.nabillnaid', '!=', 1)
                    ->where('tblenroled.istransferedbilling', '!=', 1)
                    ->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblenroled.attendance_status', '!=', 1)
                    ->where('tbltraineeaccount.nationalityid', 51)
                    ->where('tbltraineeaccount.company_id', 89)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');
                if ($currentWeek !== null) {
                    $query->where('tblcourseschedule.batchno', $currentWeek);
                }
            });
        }

        //Get IOTC schedule if there is trainee
        if ($search == null && $billingStatusId == 1) {
            $query->orWhere(function ($query) use ($billingStatusId, $currentWeek) {
                $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                    ->where('tblenroled.deletedid', '!=', 1)
                    ->where('tblenroled.dropid', '!=', 1)
                    ->where('tblenroled.IsRemedial', '!=', 1)
                    ->where('tblenroled.nabillnaid', '!=', 1)
                    ->where('tblenroled.istransferedbilling', '!=', 1)
                    ->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblenroled.attendance_status', '!=', 1)
                    ->where('tbltraineeaccount.company_id', 3)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');
                if ($currentWeek !== null) {
                    $query->where('tblcourseschedule.batchno', $currentWeek);
                }
            });
        }

        //Get NSMI schedule if there is trainee
        if ($search == null || in_array(strtolower($search), ['nyk-fil', 'nyk', 'fil', 'ship', 'nsmi'])) {
            $query->orWhere(function ($query) use ($billingStatusId, $currentWeek) {
                $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                    ->where('tblenroled.deletedid', '!=', 1)
                    ->where('tblenroled.dropid', '!=', 1)
                    ->where('tblenroled.nabillnaid', '!=', 1)
                    ->where('tblenroled.IsRemedial', '!=', 1)
                    ->where('tblenroled.istransferedbilling', '!=', 1)
                    ->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblenroled.attendance_status', '!=', 1)
                    ->where('tbltraineeaccount.company_id', '=', 1)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-03-11')
                    ->whereIn('tblcourseschedule.courseid', [91, 92, 88]);
                if ($currentWeek !== null) {
                    $query->where('tblcourseschedule.batchno', $currentWeek);
                }
            });
        }

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
            'tblenroled.billingstatusid',
            'tblenroled.companyid AS enroledcompanyid',
            'tblenroled.nabillnaid',
            'tblenroled.billing_updated_at'
        )
            ->distinct(['tblcourseschedule.scheduleid', 'tblcompany.company'])
            ->orderBy('tblcourseschedule.startdateformat', 'ASC');

        return $query;
    }

    public function ScheduleListQuery($currentWeek = null, $billingStatusId, $search = null)
    {
        $authorizeJISS = Gate::allows('authorizeAdminComponents', 163);
        $authorizeStandard = Gate::allows('authorizeAdminComponents', 164);
        $authorizeALL = Gate::allows('authorizeAdminComponents', 162);

        if ($authorizeALL || $authorizeJISS || $authorizeStandard) {
            $nykComp = tblnyksmcompany::getcompanyid();
            $avoidcomp = [...$nykComp, 1, 3];
            $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
                ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
                ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
                ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
                ->join('tblcompanycourse', 'tblcompany.companyid', '=', 'tblcompanycourse.companyid')
                ->where(function ($query) use ($billingStatusId, $search, $currentWeek, $avoidcomp, $authorizeALL, $authorizeJISS, $authorizeStandard) {
                    $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                        ->where('tblenroled.dropid', '!=', 1)
                        ->where('tblenroled.deletedid', '!=', 1)
                        ->where('tblenroled.istransferedbilling', '!=', 1)
                        ->where('tblenroled.nabillnaid', '!=', 1);

                    if (!$billingStatusId >= 6) {
                        $query->where('tblenroled.IsRemedial', '!=', 1);
                    }

                    $query->where('tblenroled.reservationstatusid', '!=', 4)
                        ->where('tblenroled.attendance_status', '!=', 1)
                        ->whereNotIn('tbltraineeaccount.company_id', $avoidcomp)
                        ->where('tblcourseschedule.startdateformat', '>', '2024-05-01');

                    if ($authorizeALL) {
                        $query->whereNotIn('tblcourses.coursetypeid', [7]);
                    } elseif ($authorizeJISS) {
                        $query->whereIn('tblcourses.coursetypeid', [5]);
                    } elseif ($authorizeStandard) {
                        $query->whereNotIn('tblcourses.coursetypeid', [7, 5]);
                    }

                    $query->where('tblcompany.company', 'LIKE', '%' . $search . '%');
                    if ($currentWeek !== null) {
                        $query->where('tblcourseschedule.batchno', $currentWeek);
                    }
                });


            if ($authorizeALL || $authorizeStandard) {
                //Get NYKSM schedule if there is trainee
                if ($search == null) {
                    $valueToRemove = 89;

                    $array = array_filter($nykComp, function ($item) use ($valueToRemove) {
                        return $item !== $valueToRemove;
                    });

                    // Reindex array
                    $nykCompremoveNYKLINE = array_values($array);

                    $query->orWhere(function ($query) use ($billingStatusId, $currentWeek, $nykCompremoveNYKLINE) {
                        $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                            ->where('tblenroled.deletedid', '!=', 1)
                            ->where('tblenroled.dropid', '!=', 1)
                            ->where('tblenroled.IsRemedial', '!=', 1)
                            ->where('tblenroled.istransferedbilling', '!=', 1)
                            ->where('tblenroled.nabillnaid', '!=', 1)
                            ->where('tblenroled.reservationstatusid', '!=', 4)
                            ->where('tblenroled.attendance_status', '!=', 1)
                            ->whereIn('tbltraineeaccount.company_id', $nykCompremoveNYKLINE)
                            ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');

                        if ($currentWeek !== null) {
                            $query->where('tblcourseschedule.batchno', $currentWeek);
                        }
                    });
                }

                //Get NYKLINE schedule if there is japanese trainee
                if ($search == null || in_array(strtolower($search), ['nyk', 'nykline', 'line', 'nykline'])) {
                    $query->orWhere(function ($query) use ($billingStatusId, $currentWeek) {
                        $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                            ->where('tblenroled.deletedid', '!=', 1)
                            ->where('tblenroled.dropid', '!=', 1)
                            ->where('tblenroled.IsRemedial', '!=', 1)
                            ->where('tblenroled.nabillnaid', '!=', 1)
                            ->where('tblenroled.istransferedbilling', '!=', 1)
                            ->where('tblenroled.reservationstatusid', '!=', 4)
                            ->where('tblenroled.attendance_status', '!=', 1)
                            ->where('tbltraineeaccount.nationalityid', 51)
                            ->where('tbltraineeaccount.company_id', 89)
                            ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');
                        if ($currentWeek !== null) {
                            $query->where('tblcourseschedule.batchno', $currentWeek);
                        }
                    });
                }

                //Get IOTC schedule if there is trainee
                if ($search == null) {
                    $query->orWhere(function ($query) use ($billingStatusId, $currentWeek) {
                        $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                            ->where('tblenroled.deletedid', '!=', 1)
                            ->where('tblenroled.dropid', '!=', 1)
                            ->where('tblenroled.IsRemedial', '!=', 1)
                            ->where('tblenroled.nabillnaid', '!=', 1)
                            ->where('tblenroled.istransferedbilling', '!=', 1)
                            ->where('tblenroled.reservationstatusid', '!=', 4)
                            ->whereNotIn('tblcourses.coursetypeid', [3, 4])
                            ->where('tblenroled.attendance_status', '!=', 1)
                            ->where('tbltraineeaccount.company_id', 3)
                            ->where('tblcourseschedule.startdateformat', '>', '2024-05-12');
                        if ($currentWeek !== null) {
                            $query->where('tblcourseschedule.batchno', $currentWeek);
                        }
                    });
                }

                //Get NSMI schedule if there is trainee
                if ($search == null || in_array(strtolower($search), ['nyk-fil', 'nyk', 'fil', 'ship', 'nsmi'])) {
                    $query->orWhere(function ($query) use ($billingStatusId, $currentWeek) {
                        $query->where('tblenroled.billingstatusid', '=', $billingStatusId)
                            ->where('tblenroled.deletedid', '!=', 1)
                            ->where('tblenroled.dropid', '!=', 1)
                            ->where('tblenroled.nabillnaid', '!=', 1)
                            ->where('tblenroled.IsRemedial', '!=', 1)
                            ->where('tblenroled.istransferedbilling', '!=', 1)
                            ->where('tblenroled.reservationstatusid', '!=', 4)
                            ->where('tblenroled.attendance_status', '!=', 1)
                            ->where('tbltraineeaccount.company_id', '=', 1)
                            ->where('tblcourseschedule.startdateformat', '>', '2024-03-11')
                            ->whereIn('tblcourseschedule.courseid', [91, 92, 88]);
                        if ($currentWeek !== null) {
                            $query->where('tblcourseschedule.batchno', $currentWeek);
                        }
                    });
                }

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
                    'tblenroled.billingstatusid',
                    'tblenroled.companyid AS enroledcompanyid',
                    'tblenroled.nabillnaid',
                    'tblenroled.billing_updated_at'
                )
                    ->distinct(['tblcourseschedule.scheduleid', 'tblcompany.company'])
                    ->orderBy('tblcourseschedule.startdateformat', 'ASC');
            }

            return $query;
        } else {
            $authorizeJISS = Gate::authorize('authorizeAdminComponents', 163);
        }
    }

    public function getSerialNumber($scheduleId, $companyId)
    {
        $nykComp = tblnyksmcompany::getcompanyid();
        if (in_array($companyId, $nykComp)) {
            $serialNumber = tblenroled::with('trainee.vessel')
                ->select('billingserialnumber')
                ->where('scheduleid', $scheduleId)
                ->whereNotNull('billingserialnumber')
                ->where('dropid', '!=', 1)
                ->where('deletedid', '!=', 1)
                ->where('istransferedbilling', '!=', 1)
                ->where('nabillnaid', '!=', 1)
                ->where('reservationstatusid', '!=', 4)
                ->where('attendance_status', '!=', 1)
                ->whereHas('trainee', function ($query) use ($nykComp) {
                    $query->whereIn('company_id', $nykComp)
                        ->where('nationalityid', '!=', 51);
                })
                ->groupBy('billingserialnumber')
                ->get()
                ->pluck('billingserialnumber');

            //Get Remedial Trainees
            $serialNumber2 = tblenroled::with('trainee.vessel')
                ->select('billingserialnumber')
                ->where('IsRemedial', 1)
                ->where('remedial_sched', $scheduleId)
                ->whereNotNull('billingserialnumber')
                ->where('dropid', '!=', 1)
                ->where('deletedid', '!=', 1)
                ->where('istransferedbilling', '!=', 1)
                ->where('nabillnaid', '!=', 1)
                ->where('attendance_status', '!=', 1)
                ->where('reservationstatusid', '!=', 4)
                ->whereHas('trainee', function ($query) use ($nykComp) {
                    $query->whereIn('company_id', $nykComp)
                        ->where('nationalityid', '!=', 51);
                })
                ->groupBy('billingserialnumber')
                ->get()
                ->pluck('billingserialnumber');
        } elseif ($companyId == 89) {
            $serialNumber = tblenroled::with('trainee.vessel')
                ->select('billingserialnumber')
                ->where('scheduleid', $scheduleId)
                ->whereNotNull('billingserialnumber')
                ->where('dropid', '!=', 1)
                ->where('deletedid', '!=', 1)
                ->where('istransferedbilling', '!=', 1)
                ->where('nabillnaid', '!=', 1)
                ->where('reservationstatusid', '!=', 4)
                ->where('attendance_status', '!=', 1)
                ->whereHas('trainee', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId)
                        ->where('nationalityid', 51);
                })
                ->groupBy('billingserialnumber')
                ->get()
                ->pluck('billingserialnumber');

            //Get Remedial Trainees
            $serialNumber2 = tblenroled::with('trainee.vessel')
                ->select('billingserialnumber')
                ->where('IsRemedial', 1)
                ->where('remedial_sched', $scheduleId)
                ->whereNotNull('billingserialnumber')
                ->where('dropid', '!=', 1)
                ->where('deletedid', '!=', 1)
                ->where('istransferedbilling', '!=', 1)
                ->where('nabillnaid', '!=', 1)
                ->where('attendance_status', '!=', 1)
                ->where('reservationstatusid', '!=', 4)
                ->whereHas('trainee', function ($query) use ($companyId) {
                    $query->where('company_id', 89)
                        ->where('nationalityid', 51);
                })
                ->groupBy('billingserialnumber')
                ->get()
                ->pluck('billingserialnumber');
        } else {
            $serialNumber = tblenroled::with('trainee.vessel')
                ->select('billingserialnumber')
                // ->where('IsRemedial', '!=', 1)
                ->where('scheduleid', $scheduleId)
                ->whereNotNull('billingserialnumber')
                ->where('dropid', '!=', 1)
                ->where('deletedid', '!=', 1)
                ->where('istransferedbilling', '!=', 1)
                ->where('nabillnaid', '!=', 1)
                ->where('reservationstatusid', '!=', 4)
                ->where('attendance_status', '!=', 1)
                ->whereHas('trainee', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->groupBy('billingserialnumber')
                ->get()
                ->pluck('billingserialnumber');

            //Get Remedial Trainees
            $serialNumber2 = tblenroled::with('trainee.vessel')
                ->select('billingserialnumber')
                ->where('IsRemedial', 1)
                ->where('remedial_sched', $scheduleId)
                ->whereNotNull('billingserialnumber')
                ->where('dropid', '!=', 1)
                ->where('deletedid', '!=', 1)
                ->where('nabillnaid', '!=', 1)
                ->where('istransferedbilling', '!=', 1)
                ->where('attendance_status', '!=', 1)
                ->where('reservationstatusid', '!=', 4)
                ->whereHas('trainee', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->groupBy('billingserialnumber')
                ->get()
                ->pluck('billingserialnumber');
        }

        $serialNumber = $serialNumber->merge($serialNumber2)->unique();



        return $serialNumber;
    }

    public function mergeCompany($data)
    {
        $arrayData = [];
        $processedScheduleIds = [];
        $index = 0;
        foreach ($data as $key => $value) {
            if (in_array($value->companyid, [262, 115, 285, 286, 287, 289, 290, 89])) {
                // Check if the scheduleid has already been processed
                if (in_array($value->scheduleid, $processedScheduleIds)) {
                    continue; // Skip this iteration if the scheduleid has already been processed
                }

                $arrayData[$index] = [
                    'coursecode' => $value->coursecode,
                    'coursename' => $value->coursename,
                    'scheduleid' => $value->scheduleid,
                    'deletedid' => $value->deletedid,
                    'batchno' => $value->batchno,
                    'startdateformat' => $value->startdateformat,
                    'enddateformat' => $value->enddateformat,
                    'company' => 'NYK SHIPMANAGEMENT (NYKSM)',
                    'companyid' => $value->companyid,
                    'billing_statement_format' => $value->billing_statement_format,
                    'billing_modified_by' => $value->billing_modified_by,
                    'nabillnaid' => $value->nabillnaid,
                    'billing_updated_at' => $value->billing_updated_at,
                ];

                // Mark this scheduleid as processed
                $processedScheduleIds[] = $value->scheduleid;
            } else {
                // Check if the scheduleid has already been processed
                if (in_array($value->scheduleid, $processedScheduleIds)) {
                    continue; // Skip this iteration if the scheduleid has already been processed
                }

                $arrayData[$index] = [
                    'coursecode' => $value->coursecode,
                    'coursename' => $value->coursename,
                    'scheduleid' => $value->scheduleid,
                    'deletedid' => $value->deletedid,
                    'batchno' => $value->batchno,
                    'startdateformat' => $value->startdateformat,
                    'enddateformat' => $value->enddateformat,
                    'company' => $value->company,
                    'companyid' => $value->companyid,
                    'billing_statement_format' => $value->billing_statement_format,
                    'billing_modified_by' => $value->billing_modified_by,
                    'nabillnaid' => $value->nabillnaid,
                    'billing_updated_at' => $value->billing_updated_at,
                ];

                // Mark this scheduleid as processed
                $processedScheduleIds[] = $value->scheduleid;
            }

            $index++;
        }

        return $arrayData;
    }

    public function mergeCompanyByShed($data)
    {
        $arrayData = [];
        $processedScheduleCompanyIds = [];
        $companyidNYKSM =  tblnyksmcompany::getcompanyid();
        foreach ($data as $key => $value) {
            if (in_array($value->companyid, $companyidNYKSM)) {
                // Check if the scheduleid and companyid combination has already been processed
                if (in_array($value->scheduleid . '-' . $value->companyid, $processedScheduleCompanyIds)) {
                    continue;
                }

                $companyName = $value->nationalityid != 51 ? 'NYK SHIPMANAGEMENT (NYKSM)' : 'NYK LINE';

                // Check if we need to merge with the existing entry
                $merged = false;
                foreach ($arrayData as &$item) {
                    if ($item['coursecode'] === $value->coursecode && $item['coursename'] === $value->coursename && $item['startdateformat'] === $value->startdateformat) {
                        if (!in_array($value->scheduleid, $item['scheduleid'])) {
                            $item['scheduleid'][] = $value->scheduleid; // Append the scheduleid to the array if not already present
                        }
                        $merged = true;
                        break;
                    }
                }

                if (!$merged) {
                    $arrayData[] = [
                        'coursecode' => $value->coursecode,
                        'coursename' => $value->coursename,
                        'scheduleid' => [$value->scheduleid], // Initialize as an array
                        'deletedid' => $value->deletedid,
                        'datebilled' => $value->datebilled,
                        'batchno' => $value->batchno,
                        'startdateformat' => $value->startdateformat,
                        'enddateformat' => $value->enddateformat,
                        'billingstatusid' => $value->billingstatusid,
                        'company' => $companyName,
                        'companyid' => $value->companyid,
                        'companyid2' => 262,
                        'enroledcompanyid' => $value->enroledcompanyid,
                        'billing_statement_format' => $value->billing_statement_format,
                        'billing_modified_by' => $value->billing_modified_by,
                        'nabillnaid' => $value->nabillnaid,
                        'billing_updated_at' => $value->billing_updated_at,
                    ];
                }

                // Mark this scheduleid and companyid combination as processed
                $processedScheduleCompanyIds[] = $value->scheduleid . '-' . $value->companyid;
            } else {
                // Check if the scheduleid and companyid combination has already been processed
                if (in_array($value->scheduleid . '-' . $value->companyid, $processedScheduleCompanyIds)) {
                    continue;
                }

                $arrayData[] = [
                    'coursecode' => $value->coursecode,
                    'coursename' => $value->coursename,
                    'scheduleid' => [$value->scheduleid], // Initialize as an array
                    'deletedid' => $value->deletedid,
                    'batchno' => $value->batchno,
                    'startdateformat' => $value->startdateformat,
                    'enddateformat' => $value->enddateformat,
                    'datebilled' => $value->datebilled,
                    'billingstatusid' => $value->billingstatusid,
                    'company' => $value->company,
                    'companyid' => $value->companyid,
                    'companyid2' => NULL,
                    'enroledcompanyid' => $value->enroledcompanyid,
                    'billing_statement_format' => $value->billing_statement_format,
                    'billing_modified_by' => $value->billing_modified_by,
                    'nabillnaid' => $value->nabillnaid,
                    'billing_updated_at' => $value->billing_updated_at,
                ];

                // Mark this scheduleid and companyid combination as processed
                $processedScheduleCompanyIds[] = $value->scheduleid . '-' . $value->companyid;
            }
        }

        return $arrayData;
    }
}
