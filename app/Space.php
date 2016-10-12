<?php

namespace App;

use App\Ships\Ship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;
use Illuminate\Database\Eloquent\morphMany;

class Space extends Model
{
	/**
	 * Fillable attributes
	 * @var array
	 */
    private $fillable = [
        'x',
        'y',
        'object_type',
        'object_id',
        'solar_system_id'
    ];

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
}
