<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The answers that belong to the question.
     */
    public function answers()
    {
        return $this->belongsToMany(Answer::class, 'answer_question')->orderBy('position');
    }

    /**
     * The answers that belong to the question.
     */
    public function survey()
    {
        return $this->belongsTo(Answer::class, 'question_survey');
    }

    /**
     * The correct answers that belong to the question and user.
     * @return BelongsToMany
     */
    public function answered()
    {
        return $this->belongsToMany(Answer::class, 'correct_answers')
            ->using(CorrectAnswer::class)
            ->wherePivot('user_id', auth()->id());
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $last = Question::latest('position')->first();
            if ($last != null)
                $model->position = $last->position + 1;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            // ... code here
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }
}
