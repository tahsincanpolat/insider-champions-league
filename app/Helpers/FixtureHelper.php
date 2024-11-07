<?php

namespace App\Helpers;

class FixtureHelper
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
}
