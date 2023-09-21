<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'type_id',
        'role_id',
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
        'password' => 'hashed',
    ];

    // ---------------------- Relationships --------------------------

    public function type()
    {
        return $this->belongsTo(Type::class,'type_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // -------------------- Attributes --------------------------------

    protected function Name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value = $this->first_name ." ".$this->last_name,
        );
    }
}
