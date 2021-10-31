<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mpociot\Teamwork\Traits\UserHasTeams;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UserHasTeams, HasRoles;

    protected $fillable = [
        'firstname',
        'surname',
        'email',
        'password',
        'uuid'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_profile_url()
    {
        return '/profile';
    }

    public function adminlte_image()
    {
        if(isset($this->avatar)){
            return asset($this->avatar);
        }
        return url('').'/vendor/adminlte/dist/img/AdminLTELogo.png';
    }

    public function adminlte_desc()
    {
        return $this->firstname.' '.$this->surname;
    }
}
