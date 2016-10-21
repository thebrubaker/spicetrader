<?php

namespace App;

use App\Chart;
use App\Commander;
use App\Ship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Location extends Model
{
    /**
     * The database table name
     * @var string
     */
    protected $table = 'space';

	/**
	 * Fillable attributes
	 * @var array
	 */
    protected $fillable = [
        'x',
        'y',
        'object_type',
        'object_id',
        'solar_system_id'
    ];

    /**
     * A location morphs to an object in space
     * @return MorphTo
     */
    public function object()
    {
        return $this->morphTo();
    }

    /**
     * The ships in space
     * @return MorphMany
     */
    public function ships()
    {
        return $this->morphMany(Ship::class, 'object');
    }

    /**
     * The space belongs to a solar system
     * @return BelongsTo
     */
    public function solar_system()
    {
    	return $this->belongsTo(SolarSystem::class);
    }

    /**
     * The planets in space
     * @return MorphMany
     */
    public function planets()
    {
    	return $this->morphMany(Planet::class, 'object');
    }

    /**
     * A location is known by many commanders
     * @return BelongsToMany
     */
    public function known_by()
    {
        return $this->belongsToMany(Commander::class, 'space_charts')->withTimestamps();
    }

    /**
     * Map a location to an array of coordinates
     * @return array
     */
    public function toCoordinates()
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }
}
