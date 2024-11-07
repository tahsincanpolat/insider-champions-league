<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Matches;
use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\FixtureHelper;

class FixturesController extends Controller
{
    public function generateFixtures()
    {
        Fixture::query()->delete();
        Matches::query()->delete();

        $teams = Team::all();
        $teamIds = $teams->pluck('id')->toArray();

        shuffle($teamIds);

        $firstHalfFixtures = FixtureHelper::generateRoundRobinFixtures($teamIds);

        $secondHalfFixtures = [];
        foreach ($firstHalfFixtures as $round) {
            $reverseRound = [];
            foreach ($round as $match) {
                $reverseRound[] = [
                    'home' => $match['away'],
                    'away' => $match['home'],
                ];
            }
            $secondHalfFixtures[] = $reverseRound;
        }

        $allFixtures = array_merge($firstHalfFixtures, $secondHalfFixtures);

        $week = 1;
        foreach ($allFixtures as $round) {
            foreach ($round as $match) {
                $matchModel = Matches::create([
                    'home_id' => $match['home'],
                    'away_id' => $match['away'],
                    'week' => $week,
                ]);

                Fixture::create([
                    'matches_id' => $matchModel->id,
                    'match_date' => Carbon::now()->addDays(($week - 1) * 7),
                    'is_played' => false,
                ]);
            }
            $week++;
        }

        return response()->json(['message' => 'Fixtures generated successfully']);
    }

    public function getFixtures()
    {
        $fixtures = Fixture::with([
            'match' => function ($query) {
                $query->with('homeTeam', 'awayTeam');
            }
        ])->orderBy('match_date')->get();

        return response()->json($fixtures);
    }
}
