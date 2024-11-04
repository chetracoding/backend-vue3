<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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
        'store_id',
        'role_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'image',
        'password'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
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

    // Store or Update staff
    public static function storeUser($request, $id = null)
    {
        $user = $request->only(['role_id', 'store_id', 'first_name', 'last_name', 'gender', 'email', 'password', 'image']);
        $user = self::updateOrCreate(['id' => $id], $user);
        return $user;
    }

    // Check user exists in store 
    public static function contains($field, $value)
    {
        return Auth::user()->store->users->contains($field, $value);
    }

    // Check the user permission
    public static function roleRequired($role)
    {
        return Auth::user()->role->name === $role;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function onesignals(): HasMany
    {
        return $this->hasMany(Onesignal::class);
    }
}
