<?php

namespace App;

use App\Location;
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
     * Create a new Schedule to a location in Location
     * @param  Location  $location
     * @return Schedule
     */
    public function plotCourse(Location $location, Carbon $depart, Carbon $arrival)
    {
        return $this->newInstance([
            'x' => $location->x,
            'y' => $location->y,
            'depart_time' => $depart,
            'arrival_time' => $arrival
        ]);
    }

    /**
     * Plot a schedule to (x, y) coordinates
     * @param  array  $coordinates
     * @param  Carbon  $depart_time
     * @param  Carbon  $arrival_time
     * @return Schedule
     */
    public function plotCoordinates(array $coordinates, Carbon $depart_time, Carbon $arrival_time)
    {
        return $this->newInstance([
            'x' => $coordinates['x'],
            'y' => $coordinates['y'],
            'depart_time' => $depart_time,
            'arrival_time' => $arrival_time
        ]);
    }
}
