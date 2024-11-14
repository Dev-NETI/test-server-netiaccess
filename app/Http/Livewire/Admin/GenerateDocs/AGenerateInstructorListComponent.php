<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblinstructor;
use Barryvdh\DomPDF\PDF;
use Livewire\Component;

class AGenerateInstructorListComponent extends Component
{
    public $datenow;
    public $instructor;
    public $counter;
    public function generatePdf()
    {
        $this->datenow = date('Y F d');

        $instructors = tblinstructor::where('is_Deleted', 0)->where('regularid', 0)->get();

        $data = [
            'instructor' => $instructors,
            'datenow' => $this->datenow,
            'counter' => $this->counter = 1
        ];

        // dd($instructor->courses);
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadview('livewire.admin.generate-docs.a-generate-instructor-list-component', $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream();
    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-instructor-list-component');
    }
}
