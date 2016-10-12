<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

  /**
   * The name of the table
   * @var string
   */
    protected $table = 'inventories';

    /**
     * The fillable attributes
     * @var array
     */
    protected $fillable = [
        'capacity',
        'mass',
        'object_id'
    ];

    /**
     * The inventory in a ship
     * @return MorphTo
     */
    public function ship()
    {
        return $this->morphTo(Ship::class, 'object');
    }

    /**
     * The inventory in a station
     * @return MorphTo
     */
    public function station()
    {
        return $this->morphTo(Station::class, 'object');
    }
}
