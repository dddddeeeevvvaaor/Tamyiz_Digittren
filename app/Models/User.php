<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// Tambahkan class Carbon
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'nama',
        'password',
        'role',
        'phone',
        'last_login_at',
        'last_login_ip',
        'last_user_agent',
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

    public function loggedIn()
    {
        $this->last_login_at = Carbon::now();
        $this->last_login_ip = request()->getClientIp();
        $this->save();
    }

    public function hasLoggedIn()
    {
        return $this->last_login_at && $this->last_login_ip
            && $this->last_login_ip != request()->getClientIp();
    }

    public function santri()
    {
        return $this->hasOne(Siswa::class, 'id_user');
    }
}
