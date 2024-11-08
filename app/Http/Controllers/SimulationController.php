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

    public function getChampionLeaguePredictions()
    {
        $simulationCount = 1000;
        $teams = Team::all();

        $championCounts = [];
        foreach ($teams as $team) {
            $championCounts[$team->id] = 0;
        }

        $remainingMatches = Matches::where('is_played', false)->get();

        $originalTeamStats = $teams->keyBy('id')->map(function ($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'points' => $team->points,
                'goals_scored' => $team->goals_scored,
                'goals_conceded' => $team->goals_conceded,
                'goal_difference' => $team->goal_difference,
            ];
        })->toArray();

        $matches = $remainingMatches->map(function ($match) {
            return [
                'home_id' => $match->home_id,
                'away_id' => $match->away_id,
                'week' => $match->week,
            ];
        })->toArray();

        for ($i = 0; $i < $simulationCount; $i++) {
            $teamsSim = $originalTeamStats;

            foreach ($matches as $match) {
                $homeTeamId = $match['home_id'];
                $awayTeamId = $match['away_id'];

                $homeScore = $this->generateScore($teamsSim[$homeTeamId]['points']);
                $awayScore = $this->generateScore($teamsSim[$awayTeamId]['points']);

                if ($homeScore > $awayScore) {
                    $teamsSim[$homeTeamId]['points'] += 3;
                }
                else if ($homeScore < $awayScore) {
                    $teamsSim[$awayTeamId]['points'] += 3;
                }
                else {
                    $teamsSim[$homeTeamId]['points'] += 1;
                    $teamsSim[$awayTeamId]['points'] += 1;
                }

                $teamsSim[$homeTeamId]['goals_scored'] += $homeScore;
                $teamsSim[$homeTeamId]['goals_conceded'] += $awayScore;
                $teamsSim[$homeTeamId]['goal_difference'] = $teamsSim[$homeTeamId]['goals_scored'] - $teamsSim[$homeTeamId]['goals_conceded'];

                $teamsSim[$awayTeamId]['goals_scored'] += $awayScore;
                $teamsSim[$awayTeamId]['goals_conceded'] += $homeScore;
                $teamsSim[$awayTeamId]['goal_difference'] = $teamsSim[$awayTeamId]['goals_scored'] - $teamsSim[$awayTeamId]['goals_conceded'];
            }

            $sortedTeams = collect($teamsSim)->sort(function ($a, $b) {
                if ($a['points'] === $b['points']) {
                    if ($a['goal_difference'] === $b['goal_difference']) {
                        return $b['goals_scored'] - $a['goals_scored'];
                    }
                    return $b['goal_difference'] - $a['goal_difference'];
                }
                return $b['points'] - $a['points'];
            });

            $championTeam = $sortedTeams->first();
            $championCounts[$championTeam['id']]++;
        }

        $predictions = [];
        foreach ($teams as $team) {
            $count = $championCounts[$team->id];
            $percentage = ($count / $simulationCount) * 100;
            $predictions[] = [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'percentage' => round($percentage, 2),
            ];
        }

        usort($predictions, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return response()->json($predictions);
    }

    private function generateScore($teamPoints)
    {
        $averageGoals = 1.5;

        $strengthFactor = $teamPoints / 10;

        $goals = random_int(0, ceil($averageGoals + $strengthFactor));

        return min(max($goals, 0), 5);
    }



}
