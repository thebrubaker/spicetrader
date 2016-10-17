<?php

namespace App;

use App\Chart;
use App\Location;
use App\Ship;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Commander extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credits'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * A commander can have many ships
     * @return HasMany
     */
    public function ships()
    {
        return $this->hasMany(Ship::class);
    }

    /**
     * A commander has many charts
     * @return HasMany
     */
    public function charts()
    {
        return $this->hasMany(Chart::class);
    }

    /**
     * A commander knows many locations through charts
     * @return BelongsToMany
     */
    public function known_locations()
    {
        return $this->belongsToMany(Location::class, 'space_charts')->withTimestamps();
    }
}
