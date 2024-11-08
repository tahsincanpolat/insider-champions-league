import { createRouter, createWebHistory } from 'vue-router'
import Teams from '../views/Teams.vue'
import Fixtures from '../views/Fixture.vue'
import Simulation from '../views/Simulation.vue'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const routes = [
  { path: '/', name: 'Teams', component: Teams },
  { path: '/fixtures', name: 'Fixtures', component: Fixtures },
  { path: '/simulation', name: 'Simulation', component: Simulation },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
    NProgress.start();
    next();
  });

  router.afterEach(() => {
    NProgress.done();
  });

export default router;
