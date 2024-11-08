<template>
  <div class="simulation container-fluid">
    <h1 class="text-center mb-4">Simulation</h1>
    <div class="simulation-container row">
      <div class="teams-table col-md-4">
        <h3>Standings</h3>
        <table class="table">
          <thead class="table-dark">
            <tr>
              <th scope="col">Team</th>
              <th scope="col">P</th>
              <th scope="col">GS</th>
              <th scope="col">GC</th>
              <th scope="col">GD</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="team in standings" :key="team.id">
              <td>{{ team.name }}</td>
              <td>{{ team.points }}</td>
              <td>{{ team.goals_scored }}</td>
              <td>{{ team.goals_conceded}}</td>
              <td>{{ team.goal_difference }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="simulation-fixture col-md-4">
        <h3>Fixture</h3>
        <div class="fixture">
            <div v-for="week in groupedFixtures" :key="week.week">
                <div class="fixture-box">
                    <h5 class="week">Week {{ week.week }}</h5>
                    <div v-for="fixture in week.fixtures" :key="fixture.id" class="teams">
                        <div class="team-name-score">
                            <span class="team1">
                                {{ fixture.home_team.name }}
                            </span>
                            <span>
                                {{
                                    fixture.home_team_goal !== null ? fixture.home_team_goal : " "
                                }}
                                -
                                {{ fixture.away_team_goal !== null ? fixture.away_team_goal : " "}}
                            </span>
                            <span class="team2">
                                {{ fixture.away_team.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
      <div class="prediction-table col-md-4">
        <h3>Champion League Prediction</h3>
        <table class="table">
          <thead class="table-dark">
            <tr>
              <th scope="col">Team</th>
              <th scope="col">%</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="prediction in championsleaguePredictions" :key="prediction.team_id">
              <td>{{ prediction.team_name }}</td>
              <td>{{ prediction.percentage }}%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="buttons-container">
        <div class="play-buttons">
            <button @click="playNextWeek">Play Next Week</button>
            <button @click="playAllWeeks">Play All Weeks</button>
        </div>
        <div class="reset-button text-right">
            <button @click="resetData">Reset Data</button>
        </div>
    </div>
  </div>
</template>

  <script>
import axios from "axios";

export default {
  name: "Simulation",
  data() {
    return {
      teams: [],
      fixtures: [],
      groupedFixtures: [],
      standings: [],
      championsleaguePredictions: []
    };
  },
  created() {
    this.fetchTeams();
    this.fetchFixtures();
  },
  methods: {
    fetchTeams() {
      axios.get("/teams")
        .then((response) => {
          this.teams = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchFixtures() {
      axios.get("/get-matches")
        .then((response) => {
          this.fixtures = response.data;
          const grouped = {};
          this.fixtures.forEach((fixture) => {
            const week = fixture.week;
            if (!grouped[week]) {
              grouped[week] = {
                week: week,
                fixtures: [],
              };
            }
            grouped[week].fixtures.push(fixture);
          });

          this.groupedFixtures = Object.values(grouped).sort(
            (a, b) => a.week - b.week
          );
          this.updateStandings();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    playNextWeek() {
      axios.post("/play-next-week")
        .then((response) => {
          this.fetchFixtures();
          this.fetchChampionLeaguePredictions();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    playAllWeeks() {
      axios.post("/play-all-weeks")
        .then((response) => {
          this.fetchFixtures();
          this.fetchChampionLeaguePredictions();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    resetData() {
      axios.post("/reset-data")
        .then((response) => {
          this.fetchFixtures();
          this.championsleaguePredictions = [];
        })
        .catch((error) => {
          console.error(error);
        });
    },
    updateStandings() {
      axios.get("/get-standings")
        .then((response) => {
          this.standings = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChampionLeaguePredictions() {
      axios
        .get("/get-championleague-predictions")
        .then((response) => {
          this.championsleaguePredictions = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

  <style scoped>
.simulation-container {
  display: flex;
}

.teams-table,
.fixtures-table,
.predictions-table {
  flex: 1;
  margin: 10px;
}

.buttons-container {
  margin-top: 20px;
}
</style>
