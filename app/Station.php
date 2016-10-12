<?php

namespace App;

use App\Inventory;
use App\Planet;
use App\Ship;
use App\Commander;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Station extends Model
{
	/**
	 * The name of the table
	 * @var string
	 */
    protected $table = 'stations';

    /**
     * The fillable attributes
     * @var array
     */
    protected $fillable = [
    	'name',
    	'mass',
    	'docking_fee',
    	'max_ships',
        'commander_id',
        'planet_id',
        'inventory_id'
    ];

    /**
     * A station belongs to a planet
     * @return BelongsTo
     */
    public function planet()
    {
    	return $this->belongsTo(Planet::class);
    }

    /**
     * A station has one commander
     * @return BelongsTo
     */
    public function commander()
    {
    	return $this->belongsTo(Commander::class);
    }

    /**
     * A station can have many ships
     * @return HasMany
     */
    public function ships()
    {
    	return $this->hasMany(Ship::class);
    }

    /**
     * A station has one inventory
     * @return HasOne
     */
    public function inventory()
    {
    	return $this->hasOne(Inventory::class);
    }
}