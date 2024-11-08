<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Team;
use App\Http\Controllers\TeamsController;
use Illuminate\Http\JsonResponse;

class TeamsControllerTest extends TestCase
{
    public function test_getTeams_returns_all_teams_as_json()
    {
        //dummy Data
        $mockedTeams = collect([
            (object) ['id' => 1, 'name' => 'Team A'],
            (object) ['id' => 2, 'name' => 'Team B'],
            (object) ['id' => 2, 'name' => 'Team C'],
            (object) ['id' => 2, 'name' => 'Team D'],
        ]);

        $teamMock = Mockery::mock('alias:App\Models\Team');
        $teamMock->shouldReceive('all')->once()->andReturn($mockedTeams);

        $controller = new TeamsController();

        $response = $controller->getTeams();

        //assert the response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($mockedTeams->toJson(), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
