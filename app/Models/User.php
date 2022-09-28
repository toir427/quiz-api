<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles, HasPermissions;

    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_USER = 'user';

    const PERMISSION_VIEW_ALL_SURVEYS = 'View All Surveys';
    const PERMISSION_EDIT_ALL_SURVEYS = 'Edit All Surveys';
    const PERMISSION_ASSIGN_ROLE = 'Assign Role';
    const PERMISSION_UNASSIGNED_ROLE = 'Unassigned Role';
    const PERMISSION_VIEW_ALL_PERMISSIONS = 'View All Permissions';
    const PERMISSION_VIEW_ALL_ROLES = 'View All Roles';
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GENDER_OTHER = 3;

    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;

    public static $roles = [
        User::ROLE_ADMIN,
        User::ROLE_MODERATOR,
        User::ROLE_USER
    ];

    public static $permissions = [
        User::ROLE_ADMIN => [
            User::PERMISSION_VIEW_ALL_SURVEYS,
            User::PERMISSION_EDIT_ALL_SURVEYS,
            User::PERMISSION_ASSIGN_ROLE,
            User::PERMISSION_UNASSIGNED_ROLE,
            User::PERMISSION_VIEW_ALL_PERMISSIONS,
            User::PERMISSION_VIEW_ALL_ROLES,
        ],
        User::ROLE_MODERATOR => [
            User::PERMISSION_VIEW_ALL_SURVEYS,
            User::PERMISSION_EDIT_ALL_SURVEYS
        ],
        User::ROLE_USER => [
            User::PERMISSION_VIEW_ALL_SURVEYS
        ]
    ];

    public static $genders = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
        self::GENDER_OTHER
    ];
    /**
     * @var mixed
     */
    public static $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'status',
        'birthday',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return Builder|HasMany
     */
    public function surveys()
    {
        return $this->hasMany(Survey::class)->orderBy('created_at', 'DESC');
    }

    /**
     * @return HasMany
     */
    public function questionnaires()
    {
        return Survey::whereIn('gender', [$this->gender, Survey::GENDER_BOTH])
            ->where('age_from', '<=', $this->getAge())
            ->where('age_to', '>=', $this->getAge())
            ->where('date_from', '<', Carbon::now())
            ->where('date_to', '>', Carbon::now())
            ->where('status', '=', Survey::STATUS_ACTIVE)
            ->orderBy('created_at', 'DESC');
    }

    public function getAge()
    {
        return Carbon::parse($this->attributes['birthday'])->age;
    }

    /**
     * The correct answers that belong to the question and user.
     * @return BelongsToMany
     */
    public function correctAnswers()
    {
        return $this->belongsToMany(Answer::class, 'correct_answers')->withPivot('answer');
    }

    public function answers()
    {
        return $this->hasMany(CorrectAnswer::class);
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // ... code here
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
