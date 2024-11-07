<template>
    <div>
      <h1>Teams</h1>
      <ul>
        <li v-for="team in teams" :key="team.id">{{ team.name }}</li>
      </ul>
      <button @click="generateFixtures">Generate Fixtures</button>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    name: 'Teams',
    data() {
      return {
        teams: [],
      };
    },
    created() {
      this.fetchTeams();
    },
    methods: {
      fetchTeams() {
        axios.get('/teams')
          .then(response => {
            console.log(response)
            this.teams = response.data;
          })
          .catch(error => {
            console.error(error);
          });
      },
      generateFixtures() {
        axios.post('/generate-fixtures')
          .then(() => {
            this.$router.push({ name: 'Fixtures' });
          })
          .catch(error => {
            console.error(error);
          });
      },
    },
  };
  </script>

  <style scoped>
  </style>
