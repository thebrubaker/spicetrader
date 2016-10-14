<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ship extends Model
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
    function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->navigation = app()->make(NavigationSystem::class);
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
     * @return MorphTo
     */
    public function location()
    {
    	return $this->morphTo(Space::class, 'object');
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
     * A ship has one schedule
     * @return HasOne
     */
    public function destination()
    {
        return $this->schedule->destination;
    }
}
