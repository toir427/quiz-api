<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The questions that belong to the answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'answer_question');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'correct_answers');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $last = Answer::latest('position')->first();
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
