<?php

namespace App\Http\Livewire\Admin\Reports\Batch;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use App\Models\tblscheduleattendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Livewire\Component;
use ZipArchive;

class ADownloadAttendanceComponent extends Component
{
    public $selected_batch;
    public $att_trainees;
    public $dateRange = [];
    public $attendanceData;
    public $companyid;

    public function export($selected_batch)
    {
        $this->selected_batch = $selected_batch;

        // Cache course types for 10 minutes
        $course_type = Cache::remember('course_types', 600, function () {
            return tblcoursetype::orderBy('orderbyid', 'ASC')->get();
        });
        $course_type_ids = $course_type->pluck('coursetypeid')->toArray();

        $training_schedules = tblcourseschedule::addSelect([
            'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 0)
                ->where('tblenroled.deletedid', 0),
        ])->whereHas('course', function ($query) use ($course_type_ids) {
            $query->whereIn('coursetypeid', $course_type_ids);
        })
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->join('tblcoursetype', 'tblcourses.coursetypeid', '=', 'tblcoursetype.coursetypeid')
            ->where('batchno', $selected_batch)
            ->having('enrolled_pending_count', '>', 0)
            ->orderBy('tblcoursetype.orderbyid', 'ASC')
            ->orderBy('tblcourses.coursename', 'ASC')
            ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
            ->orderBy('startdateformat', 'ASC')
            ->get();

        // Temporary directory for storing PDFs
        $tempDir = storage_path('app/temp_attendance_pdfs/');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        foreach ($training_schedules as $schedule) {
            $this->att_trainees = tblenroled::where('pendingid', 0)
                ->where(function ($query) use ($schedule) {
                    $query->where('scheduleid', $schedule->scheduleid)
                        ->orWhere('remedial_sched', $schedule->scheduleid)
                        ->where(function ($subquery) {
                            $subquery->where('pendingid', 0)
                                ->orWhere('pendingid', 3);
                        });
                })
                ->where('dropid', 0)
                ->where('deletedid', 0)
                ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                ->orderBy('IsRemedial', 'desc')
                ->orderBy('tbltraineeaccount.l_name', 'asc')
                ->get();

            if ($this->att_trainees->isEmpty()) {
                continue; // Skip if no trainees are found
            }

            $startDate = Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
            $endDate = Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);
            $this->dateRange = [];

            while ($startDate <= $endDate) {
                if ($startDate->dayOfWeek !== 0 || $schedule->specialclassid == 1) {
                    $this->dateRange[$startDate->format('Y-m-d')] = $startDate->format('l');
                }
                $startDate->addDay();
            }

            $this->attendanceData = tblscheduleattendance::whereIn('traineeid', $this->att_trainees->pluck('traineeid'))
                ->whereIn('date', array_keys($this->dateRange))
                ->where('scheduleid', $schedule->scheduleid)
                ->get();

            $traineesPerPage = 10;
            $totalPages = ceil($this->att_trainees->count() / $traineesPerPage);
            $data = [
                'schedule' => $schedule,
                'att_trainees' => $this->att_trainees,
                'dateRange' => $this->dateRange,
                'attendanceData' => $this->attendanceData,
                'traineesPerPage' => $traineesPerPage,
                'totalPages' => $totalPages,
            ];

            // Generate PDF asynchronously using a job
            dispatch(function () use ($data, $tempDir, $schedule) {
                $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-attendance-component', $data);

                if ($schedule->course->courseid == 117) {
                    $baseFileName = 'ATTENDANCE_BRM_BTM_P';
                } else if ($schedule->course->courseid == 118) {
                    $baseFileName = 'ATTENDANCE_BRM_BTM';
                } else {
                    $baseFileName = 'ATTENDANCE_' . $schedule->course->coursecode;
                }

                // Check if file already exists and add a suffix if necessary
                $counter = 1;
                $pdfFilePath = $tempDir . $baseFileName . '.pdf';
                while (file_exists($pdfFilePath)) {
                    $pdfFilePath = $tempDir . $baseFileName . '_' . $counter . '.pdf';
                    $counter++;
                }

                $pdf->setPaper('a4', 'landscape')->save($pdfFilePath);
            });
        }

        // Wait until all PDFs are generated
        $this->waitForAllJobsToComplete();

        // Create a zip file
        $zipFileName = 'ATTENDANCE_FILES_' . $selected_batch . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $files = File::files($tempDir);
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }
            $zip->close();
        }

        // Delete the temporary directory and its files
        File::deleteDirectory($tempDir);

        // Return the zip file for download
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    private function waitForAllJobsToComplete()
    {
        $startTime = Carbon::now();
        $timeoutSeconds = 300; // Set a timeout limit to prevent infinite loops, e.g., 5 minutes

        while (true) {
            // Get the number of jobs that are still pending in the queue
            $pendingJobs = Queue::size();

            if ($pendingJobs === 0) {
                // All jobs are completed
                break;
            }

            // Check if the waiting time exceeded the timeout limit
            $elapsedTime = Carbon::now()->diffInSeconds($startTime);
            if ($elapsedTime > $timeoutSeconds) {
                Log::error('Job processing timeout after waiting for ' . $timeoutSeconds . ' seconds.');
                throw new \Exception('Job processing timeout.');
            }

            // Sleep for a short period before checking again
            sleep(3); // Adjust sleep time if necessary
        }
    }

    public function render()
    {
        return view('livewire.admin.reports.batch.a-download-attendance-component');
    }
}
