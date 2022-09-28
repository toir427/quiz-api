<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CorrectAnswer extends Pivot
{
    protected $table = 'correct_answers';
    public $timestamps = false;

    public function questions()
    {
        return $this->belongsTo(Question::class);
    }
}
