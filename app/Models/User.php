<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'student_id',
    'last_name',
    'first_name',
    'middle_initial',
    'student_employee_id',
    'email',
    'password',
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

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
        'password' => 'hashed',
    ];


    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
        
    }

    public function hasAnyRoles($roles)
    {
        if($this->roles()->whereIn('name', $roles)->first()){
            return true;
        }
        return false;

    }

    public function hasRole($role)
    {
        if($this->roles()->where('name', $role)->first()){
            return true;
        }
        return false;

    }

    /**
     * Get the user's full name.
     */
    public function getNameAttribute()
    {
        if (isset($this->attributes['name'])) {
            return $this->attributes['name'];
        }
        
        // Fallback for users with first_name, last_name, etc.
        if (isset($this->attributes['first_name']) || isset($this->attributes['last_name'])) {
            $parts = array_filter([
                $this->first_name ?? null,
                $this->middle_initial ?? null,
                $this->last_name ?? null,
            ]);
            return implode(' ', $parts);
        }
        
        // Final fallback to email if no name fields
        return $this->email ?? 'Unknown User';
    }
}
