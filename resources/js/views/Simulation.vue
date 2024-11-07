<template>
  <div class="simulation">
    <h1>Simulation</h1>
    <h2>Standings</h2>
    <div class="simulation-container">
      <div class="predictions-table">
        <table>
          <thead>
            <tr>
              <th>Team</th>
              <th>Point</th>
              <th>Avarage</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="team in standings" :key="team.id">
              <td>{{ team.name }}</td>
              <td>{{ team.points }}</td>
              <td>{{ team.goal_difference }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="simulation-fixture">
        <div class="fixture">
            <h1>Fixture</h1>
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

      <div class="teams-table">
        <h2>Champions Prediction</h2>
        <ul>
          <li v-for="team in teams" :key="team.id">
            {{ team.name }}
          </li>
        </ul>
      </div>
    </div>
    <div class="buttons-container">
      <button @click="playNextWeek">Play Next Week</button>
      <button @click="playAllWeeks">Play All Weeks</button>
      <button @click="resetData">Reset Data</button>
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
          console.log(this.groupedFixtures);
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
        })
        .catch((error) => {
          console.error(error);
        });
    },
    playAllWeeks() {
      axios.post("/play-all-weeks")
        .then((response) => {
          this.fetchFixtures();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    resetData() {
      axios.post("/reset-data")
        .then((response) => {
          this.fetchFixtures();
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
