import { createRouter, createWebHistory } from 'vue-router';

import Teams from '../components/Teams.vue';
import Fixtures from '../components/Fixture.vue';
import Simulation from '../components/Simulation.vue';

const routes = [
  { path: '/', name: 'Teams', component: Teams },
  { path: '/fixtures', name: 'Fixtures', component: Fixtures },
  { path: '/simulation', name: 'Simulation', component: Simulation },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
