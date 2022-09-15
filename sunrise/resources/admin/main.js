import './bootstrap.js';
import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';
import routes from './routes/index.js'
import store from './store'
import { GUEST_PERMISSION } from 'lib/acl';

const router = new VueRouter({
  linkActiveClass: "active",
  mode: 'history',
  routes,
});

/**
 * Check authorization for all routes.
 */
router.beforeEach((to, from, next) => {
  const permission = (to.meta||{}).permission;

  if (!permission) {
    return next();
  }

  if (to.name === 'winner-information') {
    return next();
  }

  if (to.name === '404' || store.getters['user/can'](permission)) {
    return next();
  }

  return next({ name: GUEST_PERMISSION === permission ? 'dashboard' : 'login' });
});

const createApp = () => {
  return setTimeout(() => {
    new Vue(Object.assign({}, App, {
      el: '#app',
      router,
      store,
    }));
  }, 0);
}

if (window && window.location.pathname.indexOf('/winner-information/') === 0) {
  createApp();
} else {
  store.dispatch('user/loadMe')
    .then(createApp)
    .catch(createApp)
}
