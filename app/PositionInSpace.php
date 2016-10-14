<?php

use Illuminate\Database\Eloquent\Relations\HasOne;

interface PositionInSpace {
	/**
	 * Returns the relationship between an object and it's position in space.
	 * @return HasOne
	 */
	protected function location();
}