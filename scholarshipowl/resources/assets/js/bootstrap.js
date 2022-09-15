window.SOWLStorage = window.SOWLStorageOptimized || window.SOWLStorage;

import "lib/polifylls/includes";
import "lib/polifylls/assign";

import Vuex from "vuex";
import ls from "local-storage";
import Vue from "vue";

import * as bem from "lib/utils/bem";
import VeeValidate from "lib/validation";
import "directives";
import UnloadStore from "plugins/before-unload-store";

window.Vue = Vue;

Vue.use(Vuex);
Vue.use(VeeValidate);
Vue.use(UnloadStore);

Vue.prototype.$ls = window.ls = ls;
Vue.prototype.$bem = bem;

// layze load initialization
import VueLazyload from "vue-lazyload";
Vue.use(VueLazyload, {
  lazyComponent: true
});

/**
 * Load localStorage with data from back-end.
 */
let SOWLStorage = window.SOWLStorage;

if (SOWLStorage && SOWLStorage.localStorage) {
  if (typeof SOWLStorage.localStorage === "object") {
    Object.keys(SOWLStorage.localStorage).forEach(key => {
      if(SOWLStorage.localStorage[key]) {
        ls(key, SOWLStorage.localStorage[key]);
      }
    });
  }
}
