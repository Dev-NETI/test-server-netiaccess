<?php

namespace App\Http\Livewire\Admin\Maintenance\Faq;

use App\Models\tblfaq;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class FaqComponents extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    public $faqid;
    public $question;
    public $answer;
    public $search;
    public $addquestion;
    public $addanswer;
    protected $listeners = ['deleteupdate'];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 21);
    }

    public function editfaq($faqid)
    {
        try {
            // $this->faq = tblfaq::where('hash_id', $hash_id)->first();
            $editfaq = tblfaq::where('faqid', $this->faqid)->first();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function faqupdate($id)
    {

        try {
            $faq_data = tblfaq::find($id);
            if ($faq_data) {
                // dd($faq_data);   
                $this->faqid = $id;
                $this->question = $faq_data->question;
                $this->answer = $faq_data->answer;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function faqupdate1()
    {
        try {
            $faq = tblfaq::find($this->faqid);
            $faq->question = $this->question;
            $faq->answer = $this->answer;
            $faq->save();

            return redirect()->route('a.faq');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    // public function faqupdate($id){

    //     $update_faq = tblfaq::find($id);
    //     $update_faq->question = $this->question;
    //     $update_faq->answer = $this->answer;

    //     $update_faq->save();
    // }

    public function faqadd()
    {

        try {
            $add_faq = new tblfaq;
            $add_faq->question = $this->addquestion;
            $add_faq->answer = $this->addanswer;
            $add_faq->statusid = 0;
            $add_faq->deletedid = 0;
            $add_faq->save();

            $lastInsertedFaqId = tblfaq::latest('faqid')->value('faqid');

            tblfaq::where('faqid', $lastInsertedFaqId)
                ->update(['hash_id' => hash('sha256', $lastInsertedFaqId)]);


            return redirect()->route('a.faq');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function deleteConfirmation($deleteid)
    {
        $this->dispatchBrowserEvent('confirmation', [
            'id' => $deleteid,
            'funct' => 'deleteupdate',
            'message' => 'FAQ deleted successfully!',
        ]);
    }


    public function deleteupdate($faqid)
    {
        try {
            $delete_faq = tblfaq::find($faqid);
            $delete_faq->deletedid = 1;
            $delete_faq->save();

            return redirect()->route('a.faq');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    public function render()
    {
        try {
            $faqQuery = tblfaq::where('deletedid', 0);

            if (!empty($this->search)) {
                $faqQuery->where(function ($q) {
                    $q->where('question', 'like', '%' . $this->search . '%')
                        ->orWhere('answer', 'like', '%' . $this->search . '%');
                });
            }

            $faq_maintenance = $faqQuery->paginate(10);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }


        return view('livewire.admin.maintenance.faq.faq-components', [
            "faq_maintenance" => $faq_maintenance,
        ])->layout('layouts.admin.abase');
    }
}
