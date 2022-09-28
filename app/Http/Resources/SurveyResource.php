<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'status' => $this->status,
            'gender' => $this->gender,
            'age_from' => $this->age_from,
            'age_to' => $this->age_to,
            'type' => $this->type,
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            //'created_at' => $this->created_at!=null?$this->created_at->format('Y-m-d H:i'):null,
            //'updated_at' => $this->updated_at!=null?$this->updated_at->format('Y-m-d H:i'):null,
        ];
    }
}
