<?php

namespace App\Ships;

use App\Scopes\VikingScope;
use Illuminate\Database\Eloquent\Model;

class Viking extends Ship
{
	/**
	 * Fillable attributes
	 * @var array
	 */
    private $attributes = [
    	'type' => 'viking',
    ];

    /**
     * The Viking's fuel capacity
     * @var integer
     */
    private $fuel_capacity = 500;

    /**
     * The Viking's fuel capacity
     * @var integer
     */
    private $hull_integrity = 1000;

    /**
     * The Viking's thrust
     * @var integer
     */
    private $thrust = 2;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new VikingScope);
    }
}
