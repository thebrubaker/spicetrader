<?php

namespace App;

use App\Location;
use App\Ship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chart extends Model
{
    /**
     * The database table name
     * @var string
     */
    protected $table = 'space_charts';

    /**
     * The model's fillable attributes
     * @var array
     */
    protected $fillable = [
        'location_id',
        'commander_id'
    ];

    /**
     * A chart belongs to a commander
     * @return BelongsTo
     */
    public function commander()
    {
    	return $this->belongsTo(Commander::classs);
    }

    /**
     * A chart belongs to a location in space
     * @return BelongsTo
     */
    public function location()
    {
    	return $this->belongsTo(Location::classs);
    }
}
