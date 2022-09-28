<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'question' => $this->question,
            //'position' => $this->position,
            'answer_type' => $this->answer_type,
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
