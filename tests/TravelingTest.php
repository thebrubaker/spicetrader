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
     * @return void
     */
    public function a_ship_can_travel_to_a_location()
    {
        $ship = factory(Ship::class)->create();
        $mars = factory(Planet::class, 'mars')->create();

        $ship->navigation->travelToLocation($mars->location);

        $this->assertTrue($ship->navigation->destination instanceof PositionInSpace);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function a_ship_can_travel_to_a_planet()
    {
        $ship = factory(Ship::class)->create();
        $mars = factory(Planet::class, 'mars')->create();

        $ship->navigation->travelToObject($mars);

        $this->assertTrue($ship->navigation->destination instanceof PositionInSpace);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function a_planet_has_a_list_of_arriving_ships()
    {
        $ship = factory(Ship::class)->create();
        $mars = factory(Planet::class)->create();

        $ship->navigation->travelToObject($mars);

        $this->assertTrue($mars->arriving_ships instanceof Collection);
        $this->assertEquals(1, $mars->arriving_ships->count());
        $this->assertTrue($mars->arriving_ships->first() instanceof Ship);
    }
}
