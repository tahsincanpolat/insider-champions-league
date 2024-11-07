<template>
    <div class="container fixture">
        <h1>Fixture</h1>
        <div v-if="groupedFixtures.length > 0" class="row">
            <div v-for="week in groupedFixtures" :key="week.week" class="col-md-4">
                <div class="fixture-box">
                    <h5 class="week">Week {{ week.week }}</h5>
                    <div v-for="fixture in week.fixtures" :key="fixture.id" class="teams">
                        <span class="team-name">
                            {{ fixture.match.home_team.name }} vs {{ fixture.match.away_team.name }}
                        </span>
                        <span class="date">
                            Match Date: {{ formatDate(fixture.match_date) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No fixtures created.</p>
        </div>
        <router-link to="/simulation">
            <button>Start Simulation</button>
        </router-link>
    </div>

  </template>

  <script>
  import axios from 'axios';
  import moment from 'moment';

  export default {
    name: 'Fixtures',
    data() {
      return {
        fixtures: [],
        groupedFixtures: [],
      };
    },
    created() {
      this.fetchFixtures();
    },
    methods: {
      fetchFixtures() {
        axios
          .get('/get-fixtures')
          .then((response) => {
            this.fixtures = response.data;
            const grouped = {};
            this.fixtures.forEach((fixture) => {
              const week = fixture.match.week;
              if (!grouped[week]) {
                grouped[week] = {
                  week: week,
                  fixtures: [],
                };
              }
              grouped[week].fixtures.push(fixture);
            });

            this.groupedFixtures = Object.values(grouped).sort((a, b) => a.week - b.week);
          })
          .catch((error) => {
            console.error(error);
          });
      },
      formatDate(date) {
        return moment(date).format('DD.MM.YYYY');
      },
    },
  };
  </script>

  <style scoped>
  </style>
