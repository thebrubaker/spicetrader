<?php

namespace App;

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

		throw new NavigationSystemException('Invalid destination. Location must be an instance of an ObjectInSpace, Chart, Location or array with (x, y) keys');
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
		$schedule->destination()->associate($location->object);
		$schedule->save();

		return $schedule;
	}

	/**
	 * Plot a course to a destination
	 * @param  Location  $location
	 * @return Schedule
	 */
	protected function travelToCoordinates(int $x, int $y) {
		$schedule = $this->plotCoordinates($x, $y);
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
		$arrival_time = $this->estimateArrival($location, $depart);

		$schedule = $this->schedule->plotCourse($location, $depart_time, $arrival_time);
		$schedule->ship_id = $this->ship->id;

		return $schedule;
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  array  $coordinates
	 * @return Schedule
	 */
	protected function plotCoordinates(array $coordinates, $depart = null)
	{
		$depart = $depart ? Carbon::parse($depart) : Carbon::now();
		$arrival = $this->estimateArrival($coordinates, $depart);

		$schedule = $this->schedule->plotCourse($coordinates, $depart, $arrival);
		$schedule->ship_id = $this->ship->id;

		return $schedule;
	}

	/**
	 * Estimate the arrival time to a location
	 * @param  Location  $location
	 * @param  Carbon $depart
	 * @return Carbon
	 */
	protected function estimateArrival($location, Carbon $depart)
	{
		if($location instanceof Location) {
			$location = [
				'x' => $location->x,
				'y' => $location->y,
			];
		}

		if($this->isCoordinates($location)) {
			return $depart->addHours(3);
		}
		
		throw new NavigationSystemException('First argument must be an instance of Location or an array with (x, y) keys.');
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

	/**
	 * Review charts to see if a location is a known location
	 * @param  Location  $location
	 * @return Chart|null
	 */
	protected function checkForChartedLocation(Location $location)
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
	protected function checkForLocation(Location $location)
	{
		return $this->commander->known_locations->filter(function($known_location) use ($location) {
			return $known_location->id === $location->id;
		})->first();
	}

}