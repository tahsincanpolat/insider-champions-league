<template>
    <div>
      <h1>Fikstür</h1>
      <div v-if="groupedFixtures.length > 0">
        <div v-for="week in groupedFixtures" :key="week.week">
          <h2>Week {{ week.week }}</h2>
          <ul>
            <li v-for="fixture in week.fixtures" :key="fixture.id">
              {{ fixture.match.home_team.name }} vs {{ fixture.match.away_team.name }}
              - Date: {{ formatDate(fixture.match_date) }}
              - Played: {{ fixture.is_played ? 'Yes' : 'No' }}
            </li>
          </ul>
        </div>
        <button @click="fetchFixtures">Yenile</button>
      </div>
      <div v-else>
        <p>No fixtures created.</p>
      </div>
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

            // Haftalara göre sıralama
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
