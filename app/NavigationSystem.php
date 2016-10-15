<?php

namespace App;

use App\Schedule;
use App\Space;
use Carbon\Carbon;

class NavigationSystem {

	protected $ship;

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
		$system = app()->make(self::class);
		$system->ship = $ship;

		return $system;
	}

	/**
	 * Plot a course to a destination
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function travelToLocation(Space $location) {
		$known_destination = $this->reviewCharts($location);
		
		if($known_destination) {
			$this->plotCourseToDestination($known_destination);
		}

		return $this->plotCourseToLocation($location);
	}

	/**
	 * Plot a course to a destination
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function travelToKnownDestination(PositionInSpace $destination) {
		return $this->plotCourseToDestination($destination);
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
		$schedule->save();

		return $schedule;
	}

	/**
	 * Create a new schedule for the ship to a location
	 * @param  Space  $location
	 * @return Schedule
	 */
	public function plotCourseToDestination(PositionInSpace $destination, $depart = null)
	{
		$depart = $depart ? Carbon::parse($depart) : Carbon::now();
		$arrival = $this->estimateArrival($destination->location, $depart);

		$schedule = $this->schedule->plotCourse($destination->location, $depart, $arrival);
		$schedule->ship_id = $this->ship->id;
		$schedule->destination()->associate($destination);
		$schedule->save();

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