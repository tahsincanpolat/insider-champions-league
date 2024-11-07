<?php

namespace App\Helpers;

class Helper
{
    public static function generateRoundRobinFixtures($teams)
    {
        $fixtures = [];
        $numTeams = count($teams);

        $isOdd = $numTeams % 2 != 0;
        if ($isOdd) {
            $teams[] = null;
            $numTeams++;
        }

        $numRounds = $numTeams - 1;

        $half = $numTeams / 2;
        $teamIndexes = range(0, $numTeams - 1);

        for ($round = 0; $round < $numRounds; $round++) {
            $roundFixtures = [];

            for ($i = 0; $i < $half; $i++) {
                $homeIndex = ($round + $i) % ($numTeams - 1);
                $awayIndex = ($numTeams - 1 - $i + $round) % ($numTeams - 1);

                if ($i == 0) {
                    $awayIndex = $numTeams - 1;
                }

                $home = $teams[$homeIndex];
                $away = $teams[$awayIndex];

                if ($home !== null && $away !== null) {
                    if ($round % 2 == 0) {
                        $roundFixtures[] = ['home' => $home, 'away' => $away];
                    } else {
                        $roundFixtures[] = ['home' => $away, 'away' => $home];
                    }
                }
            }

            $fixtures[] = $roundFixtures;
        }

        return $fixtures;
    }
    public static function playMatch($match)
    {
        $homeScore = rand(0, 5);
        $awayScore = rand(0, 5);

        $match->update([
            'home_team_goal' => $homeScore,
            'away_team_goal' => $awayScore,
        ]);

        $homeTeam = $match->homeTeam;
        $awayTeam = $match->awayTeam;

        if ($homeScore > $awayScore) {
            $homeTeam->points += 3;
        } elseif ($homeScore < $awayScore) {
            $awayTeam->points += 3;
        } else {
            $homeTeam->points += 1;
            $awayTeam->points += 1;
        }

        $homeTeam->goals_scored += $homeScore;
        $homeTeam->goals_conceded += $awayScore;
        $homeTeam->goal_difference = $homeTeam->goals_scored - $homeTeam->goals_conceded;

        $awayTeam->goals_scored += $awayScore;
        $awayTeam->goals_conceded += $homeScore;
        $awayTeam->goal_difference = $awayTeam->goals_scored - $awayTeam->goals_conceded;

        $homeTeam->save();
        $awayTeam->save();
    }
}
