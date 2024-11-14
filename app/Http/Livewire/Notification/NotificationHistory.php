<?php

namespace App\Http\Livewire\Notification;
use App\Models\tbllogs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Traits\WithPagination as TraitsWithPagination;

class NotificationHistory extends Component
{
    use WithPagination;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 43);
    }

    public function render()
    {
        if ($this->search) {
            $logs  = tbllogs::where('f_name', 'LIKE', '%' . $this->search . '%')
            ->orwhere('l_name', 'LIKE', '%' . $this->search . '%')
            ->orwhere('details', 'LIKE', '%' . $this->search . '%')
            ->orderBy('timestamp', 'desc')->paginate(10);
        }else{
            $logs  = tbllogs::orderBy('timestamp', 'desc')->paginate(10);
        }
        
        return view('livewire.notification.notification-history',[
            'logs' => $logs
        ])->layout('layouts.admin.abase');
    }
}
