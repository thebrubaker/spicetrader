<?php

use App\Chart;
use App\Commander;
use App\Location;
use App\Planet;
use App\Schedule;
use App\Ship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TravelingTest extends TestCase
{
    /**
     * @test
     */
    public function a_ship_can_travel_to_an_object_in_space()
    {
        $ship = factory(Ship::class)->create();
        $planet = factory(Planet::class)->create();
        $ship->location()->save(factory(Location::class)->make());
        $planet->location()->save(factory(Location::class)->make());
        $ship->commander->known_locations()->save($planet->location);

        $schedule = $ship->navigation()->travelTo($planet);

        $this->assertTrue($schedule instanceof Schedule);
        $this->assertEquals($schedule->destination, $planet);
    }

    // public function travelTo(array $location)
    // {
    //     // what is traveling?
    //     $ship = new Ship();
    //     // where are we
    //     $ship->location(); // outputs new Location();

    //     // how do we represent location?
    //     $location->object_id; // 1
    //     $location->object_type; // App\Ship
    //     $location->x; 
    //     $location->y; 
    //     $location->updated_at;
    //     $location->solar_system_id;

    //     // what are the attributes of the ship
    //     $ship->mass
    //     $ship->force
    //     $ship->etc
    //     // where is the destination
    //     $planet = new Planet();
    //     $planet->location;
    //     // when are we leaving?
    //     $now = Carbon::now();
    //     // how long does it take to get there?
    //     $ship->computer->calculateTimeToDestination($destination);
    //     $schedule = $ship->navigation->travelTo($location, $depart, $location);

    //     $ship->scanner->detectLife($radius);
    //     $ship->pilot->evasiveManeuvers();
    //     // how do we save that we are now traveling there?
    //     $schedule = Schedule($ship, $location, $departed_at, $estimated_arival, $planet);
    //     // once it has been saved (this ship is now traveling), what is returned to the user so they know it worked?
    //     return $schedule;
    // }

    /**
     * @test
     */
    public function a_ship_can_travel_to_a_space_chart()
    {
        $ship = factory(Ship::class)->create();
        $chart = factory(Chart::class)->create();

        $schedule = $ship->navigation()->travelTo($chart);

        $this->assertTrue($schedule instanceof Schedule);
        $this->assertEquals($schedule->destination->id, $chart->location->object->id);
    }

    /**
     * @test
     */
    public function a_ship_can_travel_to_a_location()
    {
        $ship = factory(Ship::class)->create();
        $location = factory(Chart::class)->create()->location;

        $schedule = $ship->navigation()->travelTo($location);

        $this->assertTrue($schedule instanceof Schedule);
        $this->assertEquals($schedule->destination->location->id, $location->id);
    }

    /**
     * @test
     */
    public function a_ship_can_travel_to_a_coordinate()
    {
        $ship = factory(Ship::class)->create();
        $chart = factory(Chart::class)->create();

        $schedule = $ship->navigation()->travelTo([ 'x' => 25, 'y' => -30 ]);

        $this->assertTrue($schedule instanceof Schedule);
        $this->assertEquals(null, $schedule->destination);
    }

    /**
     * @test
     */
    public function a_planet_has_a_list_of_arriving_ships()
    {
        $ship = factory(Ship::class)->create();
        $planet = factory(Planet::class)->create();
        $planet->location()->save(factory(Location::class)->make());

        $schedule = $ship->navigation()->travelTo($planet);

        $this->assertTrue($planet->schedules instanceof Collection);
        $this->assertEquals(1, $planet->schedules->count());
        $this->assertTrue($planet->schedules->first() instanceof Schedule);
    }
}
