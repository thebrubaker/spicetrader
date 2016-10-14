<?php

use App\Planet;
use App\Ship;
use Carbon\Carbon;
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
    public function a_ship_can_travel_to_a_location()
    {
        $ship = factory(Ship::class)->create();
        $planet = factory(Planet::class)->create();

        $ship->navigation->travelToLocation($planet->location);

        $this->assertTrue($ship->destination instanceof PositionInSpace);
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

        $ship->navigation->travelToObject($planet);

        $this->assertTrue($ship->destination instanceof PositionInSpace);
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

        $ship->navigation->travelToObject($planet);

        $this->assertTrue($planet->arriving_ships instanceof Collection);
        $this->assertEquals(1, $planet->arriving_ships->count());
        $this->assertTrue($planet->arriving_ships->first() instanceof Ship);
    }
}
