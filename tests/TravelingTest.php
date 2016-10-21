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
        $this->assertEquals($schedule->destination->id, $planet->id);
    }

    /**
     * @test
     */
    public function a_ship_can_travel_to_a_space_chart()
    {
        $ship = factory(Ship::class)->create();
        $ship->location()->save(factory(Location::class)->make());
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
        $ship->location()->save(factory(Location::class)->make());
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
        $ship->location()->save(factory(Location::class)->make());
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
        $ship->location()->save(factory(Location::class)->make());
        $planet = factory(Planet::class)->create();
        $planet->location()->save(factory(Location::class)->make());

        $schedule = $ship->navigation()->travelTo($planet);

        $this->assertTrue($planet->schedules instanceof Collection);
        $this->assertEquals(1, $planet->schedules->count());
        $this->assertTrue($planet->schedules->first() instanceof Schedule);
    }
}
