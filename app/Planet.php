<?php

namespace App;

use App\Location;
use App\Schedule;
use App\Station;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\hasMany;

class Planet extends Model implements ObjectInSpace
{
    	/**
    	 * The name of the table
    	 * @var string
    	 */
        protected $table = 'planets';

        /**
         * The fillable attributes
         * @var array
         */
        protected $fillable = [
        	'name',
        	'category'
        ];

        /**
         * A planet has many stations
         * @return HasMany
         */
        public function stations()
        {
        	return $this->hasMany(Station::class);
        }

        /**
         * The planet has one location in space
         * @return MorphOne
         */
        public function location()
        {
            return $this->morphOne(Location::class, 'object');
        }

        /**
         * The planet has one location in space
         * @return HasOne
         */
        public function schedules()
        {
            return $this->morphMany(Schedule::class, 'destination');
        }
}
