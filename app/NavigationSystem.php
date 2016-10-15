<?php

namespace App;

use App\Schedule;
use App\Ship;
use App\Space;
use Carbon\Carbon;

class NavigationSystem {

	/**
	 * The ship running the navigation system
	 * @var Ship
	 */
	protected $ship;

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
	 * @param  PositionInSpace|Space $location
	 * @return Schedule
	 */
	public function travelTo($location)
	{
		if($location instanceof PositionInSpace) {
			return $this->createScheduleToKnownLocation($location);
		}

		if($location instanceof Space) {
			return $this->travelToLocation($location);
		}

		throw new NavigationSystemException('Invalid location as argument. Location must be an instance of a PositionInSpace or Space.');
	}

	/**
	 * Plot a course to a destination
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function travelToLocation(Space $location) {
		$known_location = $this->reviewCharts($location);
		
		if($known_location) {
			return $this->createScheduleToKnownLocation($known_location);
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
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function createScheduleToUnknownLocation(Space $location) {
		$schedule = $this->plotCourseToLocation($location);
		$schedule->save();

		return $schedule;
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

	/**
	 * Create a new schedule for the ship to a location
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function plotCourseToLocation(Space $location, $depart = null)
	{
		$depart = $depart ? Carbon::parse($depart) : Carbon::now();
		$arrival = $this->estimateArrival($location, $depart);

		$schedule = $this->schedule->plotCourse($location, $depart, $arrival);
		$schedule->ship_id = $this->ship->id;

		return $schedule;
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  Space  $location
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
	 * @param  Space  $location
	 * @param  Carbon $depart
	 * @return Carbon
	 */
	public function estimateArrival(Space $location, Carbon $depart)
	{
		return $depart->addHours(3); // TODO: replace with a real check
	}

}