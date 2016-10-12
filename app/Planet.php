<?php

namespace App;

use App\Station;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\hasMany;

class Planet extends Model
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
}
