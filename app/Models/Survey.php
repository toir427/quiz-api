<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Survey extends Model
{
    use HasFactory, HasRoles;

    protected $guarded = [];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_PENDING = 3;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_BOTH = 3;

    const TYPE_INDEPENDENT = 1;
    const TYPE_DEPENDENT = 2;

    public static $types = [
        self::TYPE_INDEPENDENT,
        self::TYPE_DEPENDENT
    ];
    public static $genders = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
        self::GENDER_BOTH
    ];
    /**
     * @var mixed
     */
    public static $statuses = [
        self::STATUS_INACTIVE,
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
        self::STATUS_PENDING,
    ];

    /**
     * The roles that belong to the user.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_survey')->orderBy('position');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
