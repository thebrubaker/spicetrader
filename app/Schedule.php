<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Schedule extends Model
{
    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
    	'ship_id'
    	'destination_type'
    	'destination_id'
    	'depart_time'
    	'arrival_time'
    ];

    /**
     * Date attributes
     * @var array
     */
    protected $dates = [
        'depart_time',
        'arrival_time'
    ];

    /**
     * The schedule belongs to a ship
     * @return BelongsTo
     */
    public function ship()
    {
    	return $this->belongsTo(Ship::class);
    }

    /**
     * The destination morpgs to an object in space
     * @return MorphTo
     */
    public function destination()
    {
    	return $this->morphTo();
    }
}
