import { createRouter, createWebHistory } from 'vue-router';

import Teams from '../views/Teams.vue';
import Fixtures from '../views/Fixture.vue';
import Simulation from '../views/Simulation.vue';

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
