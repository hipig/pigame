import { createRouter, createWebHistory } from 'vue-router'

import {appRoutes} from './routes'
import createRouteGuard from "./guard";

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
      {
          path: '/',
          redirect: '/home'
      },
      ...appRoutes,
  ],
  scrollBehavior() {
      return {top: 0};
  }
});

createRouteGuard(router);

export default router
