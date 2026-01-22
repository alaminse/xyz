<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public static function countActiveSubscribers()
    {
        return self::whereHas('subscription', function ($query) {
            $query->where('start_date', '<=', now())
                ->where('end_date', '>', now())
                ->where('status', \App\Enums\Status::ACTIVE());
        })->count();
    }

    public function assignedCourses()
    {
        return $this->belongsToMany(Course::class, 'assign_to', 'user_id', 'course_id');
    }

    // User Model এ এই method টি add করুন
    public function assessmentProgress()
    {
        return $this->hasMany(UserAssessmentProgress::class);
    }

    public function completedAssessments()
    {
        return $this->hasMany(UserAssessmentProgress::class)
            ->where('status', 1);
    }

}
