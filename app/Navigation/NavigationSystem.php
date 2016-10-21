<?php

namespace App\Navigation;

use App\Chart;
use App\Commander;
use App\Location;
use App\ObjectInSpace;
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
	 * Boot the naviation system and register necessary components, such as the
	 * ship, commander, etc.
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
	 * @param  ObjectInSpace|Location $location
	 * @return Schedule
	 */
	public function travelTo($destination)
	{
		if($destination instanceof ObjectInSpace) {
			return $this->travelToObject($destination);
		}

		if($destination instanceof Chart) {
			return $this->travelToChart($destination);
		}

		if($destination instanceof Location) {
			return $this->travelToLocation($destination);
		}

		if($this->isCoordinates($destination)) {
			return $this->travelToCoordinates($destination);
		}

		throw new InvalidDestinationException('Invalid destination. Location must be an instance of an ObjectInSpace, Chart, Location or array with (x, y) keys');
	}

	/**
	 * Plot a course to a object in space
	 * @param  ObjectInSpace  $object
	 * @return Schedule
	 */
	protected function travelToObject(ObjectInSpace $object) {
		return $this->travelToLocation($object->location);
	}

	/**
	 * Plot a course to a charted location
	 * @param  Chart  $chart
	 * @return Schedule
	 */
	protected function travelToChart(Chart $chart) {
		return $this->travelToLocation($chart->location);
	}

	/**
	 * Plot a course to a charted location
	 * @param  Location  $location
	 * @return Schedule
	 */
	protected function travelToLocation(Location $location) {
		$schedule = $this->plotCourse($location);
		$schedule->ship_id = $this->ship->id;
		$schedule->destination()->associate($location->object);
		$schedule->save();

		return $schedule;
	}

	/**
	 * Plot a course to a destination
	 * @param  Location  $location
	 * @return Schedule
	 */
	protected function travelToCoordinates(array $coordinates) {
		$schedule = $this->plotCoordinates($coordinates);
		$schedule->ship_id = $this->ship->id;
		$schedule->save();

		return $schedule;
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  Location  $location
	 * @return Schedule
	 */
	protected function plotCourse(Location $location, $depart = null)
	{
		$depart_time = $depart ? Carbon::parse($depart) : Carbon::now();
		$arrival_time = $this->estimateArrival($location, $depart_time);
		
		return $this->schedule->plotCourse($location, $depart_time, $arrival_time);
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  array  $coordinates
	 * @return Schedule
	 */
	protected function plotCoordinates(array $coordinates, $depart = null)
	{
		// Turn the depart time into a Carbon object, default is now()
		$depart_time = $depart ? Carbon::parse($depart) : Carbon::now();
		// Estimate the arrival time based on where we are going, and when we are leaving
		$arrival_time = $this->estimateArrival($coordinates, $depart_time);
		
		// Now draft a new schedule using all this info
		return $this->schedule->plotCoordinates($coordinates, $depart_time, $arrival_time);
	}

	/**
	 * Estimate the arrival time to a location
	 * @param  Location  $location
	 * @param  Carbon $depart
	 * @return Carbon
	 */
	protected function estimateArrival($location, Carbon $depart)
	{
		// If this is a location, convert it to coordinates
		if($location instanceof Location) {
			$location = [
				'x' => $location->x,
				'y' => $location->y,
			];
		}

		// if location isn't coordinates at this point, throw an exception
		if(!$this->isCoordinates($location)) {
			throw new NavigationSystemException('First argument must be an instance of Location or an array with (x, y) keys.');
		}
		// Calculate the number of minutes for the ship to reach the location
		$minutes = $this->timeToTravel($this->ship, $location);
		
		// Add that to the depart time to get the arrival time
		return $depart->addMinutes($minutes);
	}

	/**
	 * Estimate the time to travel from the ship's location to coordinates
	 * @param  Ship  $ship
	 * @param  array  $coordinates
	 * @return int
	 */
	public function timeToTravel(Ship $ship, array $destination_coordinates)
	{
		$starting_coordinates = $ship->location->toCoordinates();
		// Art to fill in this part!!!
	}

	/**
	 * Check if the array is a coordinate pair
	 * @param  array  $coordinate
	 * @return boolean
	 */
	protected function isCoordinates($coordinates)
	{
		return is_array($coordinates) && array_key_exists('x', $coordinates) && array_key_exists('y', $coordinates);
	}
}