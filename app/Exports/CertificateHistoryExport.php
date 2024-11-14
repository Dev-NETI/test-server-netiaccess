<?php

namespace App\Exports;

use App\Models\tblcertificatehistory;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CertificateHistoryExport implements WithMultipleSheets
{
    use Exportable;

    protected $yearToCompare;
    protected $course_type;

    public function __construct($yearToCompare, $course_type)
    {
        $this->yearToCompare = $yearToCompare;
        $this->course_type = $course_type;
        // dd($course_type);
    }

    public function sheets(): array
    {
        // $sheets = [];

        // $sheets[] = new CertificateSheet('January', $this->yearToCompare . '01', $this->course_type);
        // $sheets[] = new CertificateSheet('February', $this->yearToCompare . '02', $this->course_type);


        // Add more sheets if needed

        for ($month = 1; $month <= 12; $month++) {
            $monthSuffix = str_pad($month, 2, '0', STR_PAD_LEFT); // Add leading zero if needed
            $sheetName = date('F', strtotime("2024-$month-01")); // Get month name from the month number

            $sheets[] = new CertificateSheet($sheetName, $this->yearToCompare . $monthSuffix, $this->course_type);
        }


        return $sheets;
    }
}

class CertificateSheet implements FromCollection, WithTitle, WithStyles
{
    protected $sheetName;
    protected $yearToCompare;
    protected $course_type;

    public function __construct($sheetName, $yearToCompare, $course_type)
    {
        $this->sheetName = $sheetName;
        $this->yearToCompare = $yearToCompare;
        $this->course_type = $course_type;
    }

    public function title(): string
    {
        return $this->sheetName;
    }
    public function collection()
    {
        // Fetch existing data
        if ($this->course_type == 3 ||   $this->course_type == 4) {
            $certificateData = tblcertificatehistory::whereRaw("SUBSTRING(registrationnumber, 1, 4) = ?", [$this->yearToCompare])->with('course', 'trainee')
                ->whereHas('course', function ($q) {
                    $q->whereIn('coursetypeid', [3, 4]);
                })->get();
        } else {
            $certificateData = tblcertificatehistory::whereRaw("SUBSTRING(registrationnumber, 1, 4) = ?", [$this->yearToCompare])->with('course', 'trainee')
                ->whereHas('course', function ($q) {
                    $q->where('coursetypeid', $this->course_type);
                })->get();
        }


        // Create a new collection with the data
        $certificates = $certificateData->map(function ($certificate) {
            return [
                'SERIAL NO.' => ($certificate->course->coursetypeid == 3 || $certificate->course->coursetypeid == 4) ? $certificate->controlnumber : 'NOT APPLICABLE',
                'CERTIFICATE NO.' => $certificate->certificatenumber,
                'REGISTRATION NO.' => $certificate->registrationnumber,
                'BATCH NO.' => strtoupper(optional(optional($certificate->enrolled)->schedule)->batchno),
                'TRAINEES NAME' => strtoupper(optional($certificate->trainee)->l_name . ', ' . $certificate->trainee->f_name . ' ' . optional($certificate->trainee)->m_name),
                'POSITION' => strtoupper($certificate->trainee->rank->rankname),
                'COURSE' => strtoupper(optional($certificate->course)->coursecode . ' / ' . optional($certificate->course)->coursename),
                'START' => strtoupper(optional(optional($certificate->enrolled)->schedule)->startdateformat),
                'END' => strtoupper(optional(optional($certificate->enrolled)->schedule)->enddateformat),
                'FLEET' => strtoupper(optional($certificate->trainee->fleet)->fleet),
                'DATE OF BIRTH' => $certificate->trainee->birthday,
                'COMPANY' => $certificate->trainee->company->company,
                'REMARKS' => optional($certificate->enrolled)->passid == 1 ? 'PASSED' : (optional($certificate->enrolled)->isRemedial == 1 ? 'REMEDIAL' : 'FAILED'),
                'DATE PRINT' => $certificate->dateprinted,
                'ISSUED BY' => $certificate->issued_by,
            ];
        });

        // Get the column names
        $columnNames = $certificates->isNotEmpty() ? array_keys($certificates->first()) : [];

        // Create a new collection with column names as the first row
        $newCollection = collect([$columnNames])->merge($certificates);

        return $newCollection;
    }

    public function styles(Worksheet $sheet)
    {
        $certificates = $this->collection()->toArray();
        $remarksColumn = array_search('REMARKS', $certificates[0]);

        // If 'REMARKS' column exists
        if ($remarksColumn !== false) {
            foreach ($certificates as $index => $certificate) {

                $sheet->getStyle("A" . ($index + 1) . ":P" . ($index + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Change border color as needed
                        ],
                    ],
                ]);

                // Skip the header row
                if ($index === 0) {
                    continue;
                }

                $remarks = $certificates[$index]['REMARKS'];

                // Apply styles based on the 'REMARKS' value
                if ($remarks === 'PASSED') {
                    $sheet->getStyle("A" . ($index + 1) . ":P" . ($index + 1))->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'B4FFB4', // Change color as needed
                                'alpha' => 0.1,     // Set opacity level (0 to 1)
                            ],
                        ],
                    ]);
                }
            }
        }

        return [
            // Other styles if needed
        ];
    }
}
