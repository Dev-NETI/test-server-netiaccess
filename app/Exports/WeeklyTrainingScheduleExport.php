<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Style\Border;

class WeeklyTrainingScheduleExport implements WithEvents
{
    public $training_schedules_type, $special_schedules, $course_type, $week,
        $date_array, $previousSundayFormatted, $online_schedule, $show_sched, $s_show_sched, $count, $format_path;
    public function __construct(array $array)
    {
        $this->training_schedules_type = $array[0];
        $this->special_schedules = $array[1];
        $this->course_type = $array[2];
        $this->week = $array[3];
        $this->date_array = $array[4];
        $this->previousSundayFormatted = $array[5];
        $this->online_schedule = $array[6];
        $this->show_sched = $array[7];
        $this->s_show_sched = $array[8];
        $this->count = $array[9];

        $this->format_path = '/weekly-training-schedule-format.xls';

        // dd($this->week, $this->previousSundayFormatted, $this->online_schedule, $this->show_sched, $this->s_show_sched, $this->count);
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads/weeklytrainingschedule' . $this->format_path));
                $event->writer->reopen($templateFile, Excel::XLS);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->populateSheet($sheet);

                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }


    private function populateSheet($sheet)
    {
        $sheet->setCellValue('A6', strtoupper($this->week->batchno));
        $sheet->setCellValue('F9', strtoupper($this->week->batchno));
        $course_type_letter = 'A';
        $course_type_num = 14;
        $sched_num = ['G', 'H', 'I', 'J', 'K', 'L'];
        foreach ($sched_num as $key => $day) {
            if ($key == 0) {
                $sheet->setCellValue('F11', $this->previousSundayFormatted);
                $sheet->setCellValue($day . '11', $this->date_array[$key]);
            } else {
                $sheet->setCellValue($day . '11', $this->date_array[$key]);
            }
        }
        $count_arr = 0;

        foreach ($this->training_schedules_type as $index => $training_schedules) {
            $sheet->setCellValue($course_type_letter . $course_type_num, strtoupper($this->course_type->where('coursetypeid', $index)->first()->coursetype));
            $sheet->mergeCells($course_type_letter . $course_type_num . ':' . 'S' . $course_type_num);

            // Get style and apply alignment settings for course type
            $style = $sheet->getStyle($course_type_letter);
            $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // Increment row index for course type
            $course_type_num++;

            // Iterate through training schedules for the current course type
            foreach ($training_schedules as $key => $training_schedule) {
                // Set cell value for course code
                $sheet->setCellValue('A' . $course_type_num, strtoupper($training_schedule->course->coursecode));
                $sheet->setCellValue('B' . $course_type_num, strtoupper($training_schedule->course->coursename));
                $sheet->setCellValue('C' . $course_type_num, strtoupper($training_schedule->course->trainingdays));
                $sheet->setCellValue('D' . $course_type_num, strtoupper($training_schedule->course->minimumtrainees));
                $sheet->setCellValue('E' . $course_type_num, strtoupper($training_schedule->course->maximumtrainees));


                foreach ($this->show_sched[$index][$key] as $schedIndex => $sched) {
                    // Adjust the index to get the correct column letter for scheduling
                    $schedColumn = $sched_num[$schedIndex];

                    // Check if $sched is 'P' or 'O' and set cell value accordingly
                    if ($sched == 'P') {
                        $sheet->setCellValue($schedColumn . $course_type_num, 'P');
                        $present_letter = $schedColumn . $course_type_num;
                        $style = $sheet->getStyle($present_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFFFCC'], // Light Yellow color
                            ],
                        ]);
                    } elseif ($sched == 'O') {
                        $sheet->setCellValue($schedColumn . $course_type_num, 'O');
                        $online_letter = $schedColumn . $course_type_num;
                        $style = $sheet->getStyle($online_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'ADD8E6'], // Light Blue color
                            ],
                        ]);
                    }

                    $additionalColumns = ['F', 'L'];
                    foreach ($additionalColumns as $additionalColumn) {
                        $columnLetter = $additionalColumn . $course_type_num;
                        $style = $sheet->getStyle($columnLetter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    }
                }

                if ($index == 1) {

                    if ($training_schedule->instructorid != 93 && $training_schedule->assessorid != 93) {
                        if ($training_schedule->instructorid != 93) {
                            $sheet->setCellValue('M' . $course_type_num,  strtoupper(optional(optional($training_schedule->instructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()));
                            $sheet->mergeCells('M' . $course_type_num . ':' . 'N' . $course_type_num);
                        } else {
                            $sheet->setCellValue('M' . $course_type_num, 'TBA');
                            $sheet->mergeCells('M' . $course_type_num . ':' . 'N' . $course_type_num);
                        }
                        if ($training_schedule->ins_license) {
                            $sheet->setCellValue('O' . $course_type_num, date('d-M-Y', strtotime($training_schedule->ins_license->expirationdate)));
                        } else {
                            $sheet->setCellValue('O' . $course_type_num, '---');
                            $sheet->mergeCells('O' . $course_type_num . ':' . 'N' . $course_type_num);
                        }

                        if ($training_schedule->assessorid != 93) {
                            $sheet->setCellValue('P' . $course_type_num,  strtoupper(optional(optional($training_schedule->assessor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->assessor->user->formal_name()));
                        } else {
                            $sheet->setCellValue('P' . $course_type_num, 'TBA');
                        }
                        if ($training_schedule->asses_license) {
                            $sheet->setCellValue('Q' . $course_type_num, date('d-M-Y', strtotime($training_schedule->asses_license->expirationdate)));
                        } else {
                            $sheet->setCellValue('Q' . $course_type_num, '---');
                        }
                    } else {
                        if ($training_schedule->enrolled_pending_count == 0) {
                            $sheet->setCellValue('M' . $course_type_num, 'TRAINING DISSOLVED; NO ENROLLES');
                            $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                            $present_letter = 'M' . $course_type_num;
                            $style = $sheet->getStyle($present_letter);
                            $style->applyFromArray([
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color' => ['rgb' => 'FFCCCC'], // Light Red color
                                ],
                            ]);
                        } else if ($training_schedule->enrolled_pending_count < $training_schedule->course->minimumtrainees) {
                            $sheet->setCellValue('M' . $course_type_num, 'TRAINING CANCELLED; DID NOT REACHED MINIMUM REQUIRED NUMBER OF TRAINEES');
                            $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                            $present_letter = 'M' . $course_type_num;
                            $style = $sheet->getStyle($present_letter);
                            $style->applyFromArray([
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color' => ['rgb' => 'FFCCCC'], // Light Red color
                                ],
                            ]);
                        }
                    }


                    // if ($training_schedule->instructorid != 93) {
                    //     $sheet->setCellValue('M' . $course_type_num,  strtoupper($training_schedule->instructor->rank->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()));
                    //     $sheet->mergeCells('M' . $course_type_num . ':' . 'N' . $course_type_num);
                    // } else {
                    //     $sheet->setCellValue('M' . $course_type_num, 'TBA');
                    //     $sheet->mergeCells('M' . $course_type_num . ':' . 'N' . $course_type_num);
                    // }
                    // if ($training_schedule->ins_license) {
                    //     $sheet->setCellValue('O' . $course_type_num, date('d-M-Y', strtotime($training_schedule->ins_license->expirationdate)));
                    // } else {
                    //     $sheet->setCellValue('O' . $course_type_num, '---');
                    //     $sheet->mergeCells('O' . $course_type_num . ':' . 'N' . $course_type_num);
                    // }

                    // if ($training_schedule->assessorid != 93 ) {
                    //     $sheet->setCellValue('P' . $course_type_num,  strtoupper($training_schedule->assessor->rank->rankacronym) . ' ' . strtoupper($training_schedule->assessor->user->formal_name()));
                    // } else {
                    //     $sheet->setCellValue('P' . $course_type_num, 'TBA');
                    // }
                    // if ($training_schedule->asses_license) {
                    //     $sheet->setCellValue('Q' . $course_type_num, date('d-M-Y', strtotime($training_schedule->asses_license->expirationdate)));
                    // } else {
                    //     $sheet->setCellValue('Q' . $course_type_num, '---');
                    // }




                    if ($training_schedule->course->mode && $training_schedule->room) {
                        $sheet->setCellValue('R' . $course_type_num, strtoupper($training_schedule->course->mode->modeofdelivery) . ' / ' . strtoupper(substr($training_schedule->room->room, 0, 3)));
                    } else {
                        $sheet->setCellValue('R' . $course_type_num, 'TBA');
                    }

                    if ($training_schedule->course->maximumtrainees < $training_schedule->enrolled_pending_count) {
                        $cell = 'S' . $course_type_num;
                        $sheet->setCellValue($cell, $training_schedule->enrolled_pending_count);

                        // Get the style of the cell and apply the formatting
                        $cellStyle = $sheet->getStyle($cell);
                        $cellStyle->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else {
                        $sheet->setCellValue('S' . $course_type_num, $training_schedule->enrolled_pending_count);
                    }
                } else {

                    if ($training_schedule->instructor->user->user_id != 93 && $training_schedule->alt_instructorid != 93 && $training_schedule->alt_instructorid != null && $training_schedule->instructor->user->user_id != null) {
                        $sheet->setCellValue('M' . $course_type_num,  strtoupper(optional(optional($training_schedule->instructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()) . ' & ' . strtoupper(optional(optional($training_schedule->altinstructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->altinstructor->user->formal_name()));
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                    } else if (optional($training_schedule->instructor->user)->user_id != 93) {
                        $sheet->setCellValue('M' . $course_type_num,  strtoupper(optional(optional($training_schedule->instructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()));
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                    } else if ($training_schedule->enrolled_pending_count == 0) {
                        $sheet->setCellValue('M' . $course_type_num, 'TRAINING DISSOLVED; NO ENROLLES');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                        $present_letter = 'M' . $course_type_num;
                        $style = $sheet->getStyle($present_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else if ($training_schedule->enrolled_pending_count <= $training_schedule->course->minimumtrainees) {
                        $sheet->setCellValue('M' . $course_type_num, 'TRAINING CANCELLED; DID NOT REACHED MINIMUM REQUIRED NUMBER OF TRAINEES');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                        $present_letter = 'M' . $course_type_num;
                        $style = $sheet->getStyle($present_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else {
                        $sheet->setCellValue('M' . $course_type_num, 'TBA');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                    }

                    if ($training_schedule->course->mode && $training_schedule->room) {
                        $sheet->setCellValue('R' . $course_type_num, strtoupper($training_schedule->course->mode->modeofdelivery) . ' / ' . strtoupper(substr($training_schedule->room->room, 0, 3)));
                    } else {
                        $sheet->setCellValue('R' . $course_type_num, 'TBA');
                    }

                    if ($training_schedule->course->maximumtrainees < $training_schedule->enrolled_pending_count) {
                        $cell = 'S' . $course_type_num;
                        $sheet->setCellValue($cell, $training_schedule->enrolled_pending_count);

                        // Get the style of the cell and apply the formatting
                        $cellStyle = $sheet->getStyle($cell);
                        $cellStyle->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else {
                        $sheet->setCellValue('S' . $course_type_num, $training_schedule->enrolled_pending_count);
                    }
                }


                $course_type_num++;
            }
        }

        if ($this->special_schedules->count() != 0) {
            $sheet->setCellValue($course_type_letter . $course_type_num, 'SPECIAL SCHEDULE');
            $sheet->mergeCells($course_type_letter . $course_type_num . ':' . 'S' . $course_type_num);

            // Get style and apply alignment settings for course type
            $style = $sheet->getStyle($course_type_letter);
            $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $course_type_num++;

            foreach ($this->special_schedules as $key => $training_schedule) {
                // Set cell value for course code
                $sheet->setCellValue('A' . $course_type_num, strtoupper($training_schedule->course->coursecode));
                $sheet->setCellValue('B' . $course_type_num, strtoupper($training_schedule->course->coursename));
                $sheet->setCellValue('C' . $course_type_num, strtoupper($training_schedule->course->trainingdays));
                $sheet->setCellValue('D' . $course_type_num, strtoupper($training_schedule->course->minimumtrainees));
                $sheet->setCellValue('E' . $course_type_num, strtoupper($training_schedule->course->maximumtrainees));


                foreach ($this->s_show_sched[$key] as $schedIndex => $sched) {
                    // Adjust the index to get the correct column letter for scheduling
                    $schedColumn = $sched_num[$schedIndex];

                    // Check if $sched is 'P' or 'O' and set cell value accordingly
                    if ($sched == 'P') {
                        $sheet->setCellValue($schedColumn . $course_type_num, 'P');
                        $present_letter = $schedColumn . $course_type_num;
                        $style = $sheet->getStyle($present_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFFFCC'], // Light Yellow color
                            ],
                        ]);
                    } elseif ($sched == 'O') {
                        $sheet->setCellValue($schedColumn . $course_type_num, 'O');
                        $online_letter = $schedColumn . $course_type_num;
                        $style = $sheet->getStyle($online_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'ADD8E6'], // Light Blue color
                            ],
                        ]);
                    }

                    $additionalColumns = ['F', 'L'];
                    foreach ($additionalColumns as $additionalColumn) {
                        $columnLetter = $additionalColumn . $course_type_num;
                        $style = $sheet->getStyle($columnLetter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    }
                }

                if ($index == 1) {
                    if ($training_schedule->instructor->user && $training_schedule->instructor->rank->rankid !== 449) {
                        $sheet->setCellValue('M' . $course_type_num,  strtoupper(optional(optional($training_schedule->instructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()));
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'N' . $course_type_num);
                    } else {
                        $sheet->setCellValue('M' . $course_type_num, 'TBA');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'N' . $course_type_num);
                    }
                    if ($training_schedule->ins_license) {
                        $sheet->setCellValue('O' . $course_type_num, date('d-M-Y', strtotime($training_schedule->ins_license->expirationdate)));
                    } else {
                        $sheet->setCellValue('O' . $course_type_num, '---');
                        $sheet->mergeCells('O' . $course_type_num . ':' . 'N' . $course_type_num);
                    }

                    if ($training_schedule->assessor->user && $training_schedule->assessor->rank) {
                        $sheet->setCellValue('P' . $course_type_num,  strtoupper(optional(optional($training_schedule->assessor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->assessor->user->formal_name()));
                    } else {
                        $sheet->setCellValue('P' . $course_type_num, 'TBA');
                    }
                    if ($training_schedule->asses_license) {
                        $sheet->setCellValue('Q' . $course_type_num, date('d-M-Y', strtotime($training_schedule->asses_license->expirationdate)));
                    } else {
                        $sheet->setCellValue('Q' . $course_type_num, '---');
                    }

                    if ($training_schedule->course->mode && $training_schedule->room) {
                        $sheet->setCellValue('R' . $course_type_num, strtoupper($training_schedule->course->mode->modeofdelivery) . ' / ' . strtoupper(substr($training_schedule->room->room, 0, 3)));
                    } else {
                        $sheet->setCellValue('R' . $course_type_num, 'TBA');
                    }

                    $sheet->setCellValue('S' . $course_type_num, $training_schedule->enrolled_pending_count);
                } else {

                    if ($training_schedule->instructor->user->user_id != 93 && $training_schedule->alt_instructorid != 93) {
                        $sheet->setCellValue('M' . $course_type_num,  strtoupper(optional(optional($training_schedule->instructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()) . ' & ' . strtoupper(optional($training_schedule->altinstructor->user->rank)->rankacronym) . ' ' . strtoupper($training_schedule->altinstructor->user->formal_name()));
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                    } else if ($training_schedule->instructor->user && $training_schedule->instructor->rank->rankid !== 449) {
                        $sheet->setCellValue('M' . $course_type_num,  strtoupper(optional(optional($training_schedule->instructor)->rank)->rankacronym) . ' ' . strtoupper($training_schedule->instructor->user->formal_name()));
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                    } else if ($training_schedule->enrolled_pending_count == 0) {
                        $sheet->setCellValue('M' . $course_type_num, 'TRAINING DISSOLVED; NO ENROLLES');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                        $present_letter = 'M' . $course_type_num;
                        $style = $sheet->getStyle($present_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else if ($training_schedule->enrolled_pending_count <= $training_schedule->course->minimumtrainees) {
                        $sheet->setCellValue('M' . $course_type_num, 'TRAINING CANCELLED; DID NOT REACHED MINIMUM REQUIRED NUMBER OF TRAINEES');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                        $present_letter = 'M' . $course_type_num;
                        $style = $sheet->getStyle($present_letter);
                        $style->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else {
                        $sheet->setCellValue('M' . $course_type_num, 'TBA');
                        $sheet->mergeCells('M' . $course_type_num . ':' . 'Q' . $course_type_num);
                    }

                    if ($training_schedule->course->mode && $training_schedule->room) {
                        $sheet->setCellValue('R' . $course_type_num, strtoupper($training_schedule->course->mode->modeofdelivery) . ' / ' . strtoupper(substr($training_schedule->room->room, 0, 3)));
                    } else {
                        $sheet->setCellValue('R' . $course_type_num, 'TBA');
                    }

                    if ($training_schedule->course->maximumtrainees < $training_schedule->enrolled_pending_count) {
                        $cell = 'S' . $course_type_num;
                        $sheet->setCellValue($cell, $training_schedule->enrolled_pending_count);

                        // Get the style of the cell and apply the formatting
                        $cellStyle = $sheet->getStyle($cell);
                        $cellStyle->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFCCCC'], // Light Red color
                            ],
                        ]);
                    } else {
                        $sheet->setCellValue('S' . $course_type_num, $training_schedule->enrolled_pending_count);
                    }
                }


                $course_type_num++;
            }

            $course_type_num++;
            $sheet->setCellValue('B' . $course_type_num, 'LEGEND');
            $course_type_num++;
            $sheet->setCellValue('B' . $course_type_num, 'ONLINE CLASS');
            $online = 'B' . $course_type_num;
            $style = $sheet->getStyle($online);
            $style->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'ADD8E6'], // Light Blue color
                ],
            ]);

            $course_type_num++;
            $sheet->setCellValue('B' . $course_type_num, 'PRACTICAL CLASS');
            $practical = 'B' . $course_type_num;
            $style = $sheet->getStyle($practical);
            $style->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FFFFCC'], // Light Yellow color
                ],
            ]);
            $course_type_num += 2;
            $sheet->setCellValue('B' . $course_type_num, 'PREPARED BY:');
            $sheet->setCellValue('C' . $course_type_num, 'NOTED BY:');
            $sheet->setCellValue('L' . $course_type_num, 'APPROVED BY:');

            // Set the alignment and disable wrap text for the cells
            $alignmentStyle = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'wrapText' => false,
                ],
            ];
            $style = $sheet->getStyle('B' . $course_type_num . ':C' . $course_type_num);
            $style->applyFromArray($alignmentStyle);

            $style = $sheet->getStyle('L' . $course_type_num);
            $style->applyFromArray($alignmentStyle);

            $course_type_num += 2;
            $sheet->setCellValue('B' . $course_type_num, 'JLR');
            $sheet->setCellValue('C' . $course_type_num, 'MAM');
            $sheet->setCellValue('L' . $course_type_num, 'CLG/MDA');

            // Set the alignment and disable wrap text for the cells
            $style = $sheet->getStyle('B' . $course_type_num . ':C' . $course_type_num);
            $style->applyFromArray($alignmentStyle);

            $style = $sheet->getStyle('L' . $course_type_num);
            $style->applyFromArray($alignmentStyle);
        }
    }
}
