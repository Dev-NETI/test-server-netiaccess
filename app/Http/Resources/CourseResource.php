<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'courseid' => $this->courseid,
            'coursecode' => $this->coursecode,
            'coursename' => $this->coursename,
            'trainingdays' => $this->trainingdays,
            'minimumtrainees' => $this->minimumtrainees,
            'maximumtrainees' => $this->maximumtrainees,
            'coursetype' => $this->type->coursetype
        ];
    }
}
