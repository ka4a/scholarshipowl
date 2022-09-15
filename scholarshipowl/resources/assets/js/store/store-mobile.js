import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

import options                from "./options";
import account                from "./account";
import modal                  from "./modal";
import screen                 from "./screen";
import content                from "./content";

export default new Vuex.Store({
  strict: process.env.NODE_ENV !== "production",
  modules: {
    options,
    account,
    modal,
    screen,
    content
  }
});