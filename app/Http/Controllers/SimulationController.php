<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;
use App\Models\Team;
use App\Helpers\Helper;

class SimulationController extends Controller
{
    public function playNextWeek()
    {
        $nextWeekMatch = Matches::where('is_played', false)
            ->orderBy('week')
            ->first();

        if ($nextWeekMatch) {
            $week = $nextWeekMatch->week;

            $matches = Matches::where('week', $week)
                ->where('is_played', false)
                ->get();

            foreach ($matches as $match) {
                Helper::playMatch($match);

                $match->is_played = true;
                $match->save();
            }

            return response()->json(['message' => 'Next week played successfully']);
        } else {
            return response()->json(['message' => 'All weeks have been played'], 200);
        }
    }

    public function playAllWeeks()
    {
        $matches = Matches::where('is_played', false)
            ->orderBy('week')
            ->get();

        foreach ($matches as $match) {
            Helper::playMatch($match);
            $match->is_played = true;
            $match->save();
        }

        return response()->json(['message' => 'All weeks played successfully']);
    }

    public function resetData()
    {
        Matches::query()->update([
            'home_team_goal' => null,
            'away_team_goal' => null,
            'is_played' => false,
        ]);

        Team::query()->update([
            'points' => 0,
            'goals_scored' => 0,
            'goals_conceded' => 0,
            'goal_difference' => 0,
        ]);

        return response()->json(['message' => 'Data reset successfully']);
    }

    public function getStandings()
    {
        $teams = Team::orderByDesc('points')
            ->orderByDesc('goal_difference')
            ->orderByDesc('goals_scored')
            ->get();

        return response()->json($teams);
    }

}
