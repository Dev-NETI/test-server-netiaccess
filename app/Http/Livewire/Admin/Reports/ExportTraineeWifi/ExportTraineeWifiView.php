<?php

namespace App\Http\Livewire\Admin\Reports\ExportTraineeWifi;

use Livewire\Component;
use App\Models\tblenroled;
use Illuminate\Support\Str;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportTraineeWifiView extends Component
{
    public $scheduleDropdown;
    public $traineeData = [];

    public function render()
    {
        $scheduleData = tblcourseschedule::where('startdateformat', '>', now())
            ->orderBy('startdateformat', 'asc')
            ->get()
            ->unique('batchno');

        return view(
            'livewire.admin.reports.export-trainee-wifi.export-trainee-wifi-view',
            compact('scheduleData')
        )->layout('layouts.admin.abase');
    }

    public function updatedScheduleDropdown($value)
    {
        $traineeData = tblenroled::with(['schedule.course'])
            ->where('deletedid', 0)
            ->where('pendingid', 0)
            ->whereHas('schedule', function ($query) use ($value) {
                $query->where('batchno', $value)
                    ->where('deletedid', 0);
            })
            ->get();

        if ($traineeData->isNotEmpty()) {
            // Use a chunking method to process large datasets in batches for memory efficiency
            $traineeData->chunk(100)->each(function ($chunk) {
                // Iterate through each chunk and update the required attributes
                $chunk->each(function ($trainee) {

                    if (is_null($trainee->wifi_username)) {
                        $trainee->update([
                            'wifi_username' => Str::replace(' ', '', $trainee->trainee->f_name) . "." . Str::replace(' ', '', $trainee->trainee->l_name),
                            'wifi_password' => $this->encryptTo8Chars($trainee->enroledid),
                            'wifi_expiration' => $trainee->schedule->enddateformat,
                        ]);
                    }
                });
            });

            $this->traineeData = $traineeData;
        }
    }

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

    public function download()
    {
        $templatePath = storage_path('app/public/uploads/trainee-wifi-template/Trainee-Wifi-List.csv');
        $reader = IOFactory::createReader('Csv');
        $spreadsheet = $reader->load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();
        $row = 2;
        foreach ($this->traineeData as $data) {
            $sheet->setCellValue('A' . $row, $data->wifi_username);
            $sheet->setCellValue('B' . $row, $data->trainee->full_name);
            $sheet->setCellValue('C' . $row, $data->trainee->company->company);
            $sheet->setCellValueExplicit('D' . $row, $data->trainee->mobile_number, DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, $data->trainee->email);
            $sheet->setCellValue('F' . $row, $data->wifi_password);
            $sheet->setCellValue('H' . $row, $data->wifi_expiration);
            $row++;
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setSheetIndex(0);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="Trainee Wifi List.csv"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
