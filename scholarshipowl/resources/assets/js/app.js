/**
 * Load VueJS application
 */
import "bootstrap";
import router from "router";
import store from "store";
import Vue from "vue";

Vue.config.devtools = true

import App from "components/App.vue";

new Vue({
  el: "#app",
  store,
  router,
  ...App
});
