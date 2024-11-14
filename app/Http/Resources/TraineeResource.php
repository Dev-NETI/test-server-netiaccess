<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TraineeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'trainee_id' => $this->traineeid,
            'l_name' => $this->l_name,
            'f_name' => $this->f_name,
            'm_name' => $this->m_name,
            'suffix' => $this->suffix,
            'birthday' => $this->birthday,
            'birthplace' => $this->birthplace,
            'address' => $this->address,
            'username' => $this->username,
            'contact_num' => $this->contact_num,
            'email' => $this->email,
            'rank' => $this->rank->rank,
            'company' => $this->company,
            'fleet' => $this->fleet->fleet,
            'imagepath' => $this->imagepath,
            'srn_num' => $this->srn_num,
            'street' => $this->street,
            'postal' => $this->postal

        ];
    }
}
