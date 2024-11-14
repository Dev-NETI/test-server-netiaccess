<?php

namespace App\Http\Livewire\Admin\Maintenance\Announcement;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\tblannouncement;
use Lean\ConsoleLog\ConsoleLog;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnnouncementComponents extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    public $announcement;
    public $announcementId;


    public function mount()
    {
        $this->announcementId = 1; // Set a default value if needed
        Gate::authorize('authorizeAdminComponents', 20);

        $announcement_data = tblannouncement::findOrFail($this->announcementId);
        $this->announcement = $announcement_data->announcement;
    }

    public function updateAnnouncement()
    {
        try {
            $updateAnnouncement = tblannouncement::find($this->announcementId);

            if ($updateAnnouncement) {
                // Add validation here if needed
                $updateAnnouncement->announcement = $this->announcement;
                $updateAnnouncement->save();
            }
            return redirect()->route('a.announcement');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.maintenance.announcement.announcement-components')->layout('layouts.admin.abase');
    }
}
