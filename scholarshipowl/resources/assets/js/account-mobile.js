import "lib/polifylls/includes";
import "lib/polifylls/assign";

import Vue from "vue";
import Vuex from "vuex";
import VeeValidate from "lib/validation";
import "directives";

Vue.use(Vuex);
Vue.use(VeeValidate);

import store from "store/store-mobile.js";
import Vue from "vue";

Vue.config.devtools = true

import Account from "components/Pages/AccountMobile/Account.vue";

window.SOWLStorage = window.SOWLStorage || window.SOWLStorageOptimized;

new Vue({
  el: "#app",
  store,
  ...Account
});