<template>
  <div class="teams">
    <div class="team-box">
        <h1 class="header">Teams</h1>
        <div class="team" v-for="team in teams" :key="team.id">
            <Team :team="team" />
        </div>
    </div>
    <button @click="generateFixtures">Generate Fixture</button>
  </div>
</template>

  <script>
import axios from "axios";
import Team from "../components/Team.vue";

export default {
  name: "Teams",
  components: {
    Team
  },
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
      axios.get("/teams")
        .then((response) => {
          this.teams = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    generateFixtures() {
      axios.post("/generate-fixtures")
        .then(() => {
          this.$router.push({ name: "Fixtures" });
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style scoped>
</style>
