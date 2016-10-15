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
     * A basic functional test example.
     *
     * @test
     */
    public function a_ship_can_travel_to_a_known_location()
    {
        $ship = factory(Ship::class)->create();
        $planet = factory(Planet::class)->create();
        $planet->location()->save(factory(Location::class)->make());
        $ship->commander->known_locations()->save($planet->location);

        $schedule = $ship->navigation()->travelTo($planet->location);

        $this->assertTrue($schedule instanceof Schedule);
    }

    /**
     * A basic functional test example.
     *
     * @test
     */
    public function a_ship_can_travel_to_a_planet()
    {
        $ship = factory(Ship::class)->create();
        $planet = factory(Planet::class)->create();
        $planet->location()->save(factory(Location::class)->make());

        $schedule = $ship->navigation()->travelTo($planet);

        $this->assertTrue($schedule instanceof Schedule);
    }

    /**
     * A basic functional test example.
     *
     * @test
     */
    public function a_planet_has_a_list_of_arriving_ships()
    {
        $ship = factory(Ship::class)->create();
        $planet = factory(Planet::class)->create();
        $planet->location()->save(factory(Location::class)->make());

        $schedule = $ship->navigation()->travelTo($planet);

        $planet = $planet->fresh();

        $this->assertTrue($planet->schedules instanceof Collection);
        $this->assertEquals(1, $planet->schedules->count());
        $this->assertTrue($planet->schedules->first() instanceof Schedule);
    }
}
