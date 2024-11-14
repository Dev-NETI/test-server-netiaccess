<?php

namespace App\Http\Livewire\Notification;

use App\Models\tbllogs;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class NotificationHistoryTable extends DataTableComponent
{
    protected $model = tbllogs::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Log id", "log_id")
                ->sortable(),
            Column::make("First Name", "f_name")
                ->sortable(),
            Column::make("Last Name", "l_name")
                ->sortable(),
            Column::make("Details", "details")
                ->sortable(),
            Column::make("Data", "data")
                ->format(function ($value,  $row) {
                    if ($value) {
                        $output = "";
                        foreach ($value as $key => $item) {

                            $output .= "<code><span>" . $key . "</span></code>";
                            if ($key === 'trainee_id' && is_array($item)) {
                                $output .= "<h5>";
                                if (empty($item)) {
                                    $output .= "Empty";
                                } else {
                                    foreach ($item as $trainee) {
                                        $output .= "<span>" . $trainee . "</span>";
                                        $output .= "<br>";
                                    }
                                }
                                $output .= "</h5>";
                            } else {
                                $output .= "<h5>" . $item . "</h5>";
                            }
                        }
                        return $output;
                    } else {
                        return null;
                    }
                })
                ->html(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
