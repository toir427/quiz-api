<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'status' => $this->status,
            'created_at' => $this->created_at!=null?$this->created_at->format('Y-m-d H:i'):null,
        ];
    }
}
