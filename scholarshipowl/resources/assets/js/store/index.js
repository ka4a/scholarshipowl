import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

import content                from "./content";
import account                from "./account";
import options                from "./options";
import fset                   from "./fset";
import paymentSet             from "./paymentSet";
import modal                  from "./modal";
import scholarships           from "./scholarships";
import mailbox                from "./mailbox";
import coregs                 from "./coregs";
import screen 		          from "./screen";
import settings 	          from "./settings";
import list                   from "./list";
import eligibilityCache       from "./eligibility-cache";
import cookieDisclaimer      from "./cookies-disclaimer";
import payment                from "./payment";

export default new Vuex.Store({
  strict: process.env.NODE_ENV !== "production",
  modules: {
    content,
    account,
    options,
    modal,
    fset,
    paymentSet,
    scholarships,
    mailbox,
    coregs,
    screen,
    settings,
    list,
    eligibilityCache,
    cookieDisclaimer,
    payment
  }
});
