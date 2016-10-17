<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface ObjectInSpace {
	/**
	 * Returns the relationship between an object and it's position in space.
	 * @return HasOne
	 */
	function location();
}