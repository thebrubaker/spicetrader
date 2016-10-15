<?php

namespace App;

use App\Commander;
use App\Location;
use App\Schedule;
use App\Ship;
use Carbon\Carbon;

class NavigationSystem {

	/**
	 * The ship running the navigation system
	 * @var Ship
	 */
	protected $ship;

	/**
	 * The commander running the navigation system
	 * @var Commander
	 */
	protected $commander;

	/**
	 * The model for scheduling trips
	 * @var Schedule
	 */
	protected $schedule;

	/**
	 * Constructor for the Navigation System
	 * @param Scheduler $scheduling
	 */
	function __construct(Schedule $schedule)
	{
		$this->schedule = $schedule;
	}

	/**
	 * Boot the naviation system for a Ship
	 * @param  Ship  $ship
	 * @return NavigationSystem
	 */
	public static function boot(Ship $ship)
	{
		$system = static::newInstance();
		$system->ship = $ship;
		$system->commander = $ship->commander;

		return $system;
	}

	/**
	 * Make a new instance of the class
	 * @return NavigationSystem
	 */
	public static function newInstance()
	{
		return app()->make(self::class);
	}

	/**
	 * Schedule the ship to travel to a location
	 * @param  PositionInSpace|Location $location
	 * @return Schedule
	 */
	public function travelTo($location)
	{
		if($location instanceof PositionInSpace) {
			return $this->createScheduleToKnownLocation($location);
		}

		if($location instanceof Location) {
			return $this->travelToLocation($location);
		}

		throw new NavigationSystemException('Invalid location as argument. Location must be an instance of a PositionInSpace or Location.');
	}

	/**
	 * Plot a course to a destination
	 * @param  Location  $location
	 * @return Schedule
	 */
	public function travelToLocation(Location $location) {
		$known_location = $this->checkForLocation($location);
		
		if($known_location) {
			return $this->createScheduleToKnownLocation($known_location->object);
		}
		
		return $this->createScheduleToUnknownLocation($location);
	}

	/**
	 * Plot a course to a object
	 * @param  PositionInSpace  $object
	 * @return Schedule
	 */
	public function createScheduleToKnownLocation(PositionInSpace $object) {
		$schedule = $this->plotCourseToDestination($object);
		$schedule->save();

		return $schedule;
	}

	/**
	 * Plot a course to a destination
	 * @param  Location  $location
	 * @return Schedule
	 */
	public function createScheduleToUnknownLocation(Location $location) {
		$schedule = $this->plotCourseToLocation($location);
		$schedule->save();

		return $schedule;
	}

	/**
	 * Review charts to see if a location is a known location
	 * @param  Location  $location
	 * @return Chart|null
	 */
	public function checkForChartedLocation(Location $location)
	{
		return $this->commander->charts->filter(function($chart) use ($location) {
			return $location->id === $chart->location_id;
		})->first();
	}

	/**
	 * Review charts to see if a location is a known location
	 * @param  Location  $location
	 * @return Location|null
	 */
	public function checkForLocation(Location $location)
	{
		return $this->commander->known_locations->filter(function($known_location) use ($location) {
			return $known_location->id === $location->id;
		})->first();
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  Location  $location
	 * @return Schedule
	 */
	public function plotCourseToLocation(Location $location, $depart = null)
	{
		$depart = $depart ? Carbon::parse($depart) : Carbon::now();
		$arrival = $this->estimateArrival($location, $depart);

		$schedule = $this->schedule->plotCourse($location, $depart, $arrival);
		$schedule->ship_id = $this->ship->id;

		return $schedule;
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  Location  $location
	 * @return Schedule
	 */
	public function plotCourseToDestination(PositionInSpace $destination, $depart = null)
	{
		$schedule = $this->plotCourseToLocation($destination->location, $depart);
		$schedule->destination()->associate($destination);

		return $schedule;
	}

	/**
	 * Estimate the arrival time to a location
	 * @param  Location  $location
	 * @param  Carbon $depart
	 * @return Carbon
	 */
	public function estimateArrival(Location $location, Carbon $depart)
	{
		return $depart->addHours(3); // TODO: replace with a real check
	}

}