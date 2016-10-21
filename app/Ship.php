<?php

namespace App;

use App\Location;
use App\Navigation\NavigationSystem;
use App\ObjectInSpace;
use App\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ship extends Model implements ObjectInSpace
{
	/**
	 * Table for saving models
	 * @var string
	 */
	protected $table = 'ships';

	/**
	 * Fillable attributes
	 * @var array
	 */
    protected $fillable = [
    	'name',
        'type',
        'force',
    	'fuel',
    	'mass'
    ];

    /**
     * The ship's navigation system
     * @var NavigationSystem
     */
    protected $navigation;

    /**
     * Constructor for a Ship
     * @param array $attributes
     */
    function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->navigation = NavigationSystem::boot($this);
    }

    /**
     * Return the ship's navigation system
     * @return NavigationSystem
     */
    public function navigation()
    {
        return $this->navigation;
    }

    /**
     * A ship has a location in space as an object
     * @return MorphOne
     */
    public function location()
    {
    	return $this->morphOne(Location::class, 'object');
    }

    /**
     * A ship has one schedule
     * @return HasOne
     */
    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }

    /**
     * A ship belongs to a commander
     * @return BelongsTo
     */
    public function commander()
    {
        return $this->belongsTo(Commander::class);
    }
}
