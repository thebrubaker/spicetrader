<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Schedule extends Model
{
    /**
     * The name of the database table
     * @var string
     */
    protected $table = 'ship_schedules';

    /**
     * Fillable attributes
     * @var array
     */
    protected $fillable = [
        'x',
        'y',
    	'depart_time',
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
     * The destination morphs to an object in space
     * @return MorphTo
     */
    public function destination()
    {
    	return $this->morphTo();
    }

    /**
     * Create a new Schedule to a location in Space
     * @param  Space  $location
     * @return Schedule
     */
    public function plotCourse(Space $location, Carbon $depart, Carbon $arrival)
    {
        return $this->newInstance([
            'x' => $location->x,
            'y' => $location->y,
            'depart_time' => $depart,
            'arrival_time' => $arrival
        ]);
    }
}
