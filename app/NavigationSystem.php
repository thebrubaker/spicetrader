<?php

namespace App;

use App\Space;
use App\ScheduleFactory as Scheduler;

class NavigationSystem {

	/**
	 * Constructor for the Navigation System
	 * @param Scheduler $scheduling
	 */
	function __construct(Scheduler $plot)
	{
		$this->plot = $plot;
	}

	/**
	 * Plot a course to a destination
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function travelToLocation(Space $location) {
		$known_Location = $this->reviewCharts($location);
		
		if($known_Location) {
			$this->registerDestination($known_Location)
		}

		return $this->plot->course($location);
	}

	/**
	 * Review charts to see if a location is a known location
	 * @param  Space  $location
	 * @return PositionInSpace|null
	 */
	public function reviewCharts(Space $location)
	{
		return null;
	}

	/**
	 * Register a destination object
	 * @param  PositionInSpace $destination
	 * @return PositionInSpace|null an object in space
	 */
	public function registerDestination(PositionInSpace $destination)
	{
		return null;
	}

}