<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblinstructor;
use App\Models\tblinstructorattachment;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class AGenerateInstructorAttachmentsComponent extends Component
{
    public $userid;
    public $attachmenttypeid;
    public $attachmentdetails = [];

    // public function getAttachment($userid, $attachmenttypeid){
    //     $attachmentdetails = tblinstructorattachment::where('userid', $userid)
    //     ->where('attachmenttypeid', $attachmenttypeid)
    //     ->orderBy('id', 'DESC')
    //     ->limit(1)
    //     ->get();

    //     return $attachmentdetails;
    // }


    public function generatePDF(){

        $data = tblinstructor::join('users','users.user_id', 'tblinstructor.userid')
        ->join('tblrank', 'tblrank.rankid', 'tblinstructor.rankid')
        ->whereNotIn('tblinstructor.instructorid', [93,67])
        ->orderBy('users.l_name', 'ASC')
        ->get();

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-instructor-attachments-component', [ 
            'data' => $data,
        ]);
        
        $pdf->setPaper('Legal', 'landscape');
        $pdf->setOption('filename', 'Instructor_Attachment_Summary.pdf');

        return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="Instructor_Attachment_Summary.pdf"');
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-instructor-attachments-component');
    }
}
