<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
	/**
	 * Table for saving models
	 * @var string
	 */
	private $table = 'ships';

	/**
	 * Fillable attributes
	 * @var array
	 */
    private $fillable = [
    	'name',
    	'fuel',
    	'damage'
    ];

    /**
     * Constructor
     * @param ShipSystems $systems
     */
    function __construct(ShipSystems $systems)
    {
    	$this->systems = $systems::boot($this);
    }

    /**
     * A ship has a location in space as an object
     * @return MorphTo
     */
    public function location()
    {
    	return $this->morphTo(Space::class, 'object');
    }
}
