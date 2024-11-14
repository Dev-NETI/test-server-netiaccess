<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrolledResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'enroledid' => $this->enroledid,
            'scheduleid' => $this->scheduleid,
            'courseid' => $this->courseid,
            'course_code' => $this->schedule->course->coursecode,
            'course' => $this->schedule->course->coursename,
            'start_date' => $this->schedule->startdateformat,
            'end_date' => $this->schedule->enddateformat,
            'pending_id' => $this->pendingid,
            'deleted_id' => $this->deletedid,
            'paymentmode_id' => $this->paymentmodeid,
            'payment_mode' => $this->payment->paymentmode,
            'bus_id' => $this->busid,
            'bus' =>  $this->bus->busmode,
            'dorm_id' => $this->dormid,
            'dorm' => $this->dorm->dorm
        ];
    }
}
