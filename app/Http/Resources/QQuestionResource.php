<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QQuestionResource extends JsonResource
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
            'answered' => $this->answer_type === 1
                ? optional(auth()->user()->answers()->where('question_id', '=', $this->id)->first())->answer
                : ($this->answer_type === 5 ? optional(auth()->user()->answers()->where('question_id', '=', $this->id)->first())->answer_id : $this->answered()->pluck('answer_id')),
            'answers' => QAnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
