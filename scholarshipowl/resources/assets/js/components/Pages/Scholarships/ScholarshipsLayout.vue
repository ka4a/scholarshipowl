<template>
  <div style="height: 100%;">
    <base-container>
      <pre-loader v-if="fetching" />
      <scholarships v-else />
      <login-register slot="login" />
    </base-container>
    <notif-i-os-app v-if="showNotifIOsApp" />
    <modal />
  </div>
</template>

<script>
import {mapActions} from "vuex";
import {fetchWithDalay} from "lib/utils/utils";
import { ROUTES } from "router.js";
import { NEW_SCHOLARSHIPS, SENT_SCHOLARSHIPS } from "store/scholarships";

import BaseContainer from "components/Pages/BaseContainer.vue";
import PreLoader from "components/Pages/Own/PreLoader/PreLoader.vue";
import Scholarships from "./Scholarships.vue";
import LoginRegister from "components/Layout/LoginRegister.vue";
import NotifIOsApp from "components/Pages/Own/Notifications/NotifIOsApp.vue";
import Modal from "components/Common/Modals/Modal.vue";

export default {
  components: {
    BaseContainer,
    PreLoader,
    Scholarships,
    LoginRegister,
    NotifIOsApp,
    Modal
  },
  created() {
    Vue.http.interceptors.push(() => response => {
      if((response.status === 401 && response.body === "Unauthorized.")
        || (response.body && response.body.status === 400
        && response.body.error === "Param accountId not provided")) {

        this.$store.commit("account/SET_ACCOUNT", null, {root: true});
        this.$router.push(`${ROUTES.SCHOLARSHIPS}/${ROUTES.NO_AUTHORIZED}`);
        setTimeout(() => {
          this.$store.dispatch("modal/openModal", "login", {root: true});
        }, 2000);
      }

      return response;
    });

  this.$store.dispatch("account/fetchData", ['membership'])
    .then(() =>
      Promise.all([
        this.fetchScholarships(),
        this.fetchSentScholarships(),
        this.getFsetData(['contentSet'])
      ]))
    .then(responses => {
      fetchWithDalay(1000, () => {
        this.fetching = false;

        if(!responses[0].body || responses[0].body.status !== 200)
          return;

        /**
         * Set initial tab name value depend on routing.
         * It could be SENT_SCHOLARSHIPS or NEW_SCHOLARSHIPS constants
         */
        this.setTabName();

        const scholarships = responses[0].body.data;

        this.sendShowedScholarshipIDs(scholarships);
      });
    })
    .catch(() => {
      fetchWithDalay(1000, () => {
        this.$router.push(`${ROUTES.SCHOLARSHIPS}/${ROUTES.FAILURE}`);
        this.fetching = false;
      });
    });
  },
  data() {
    return {
      fetching: true,
    }
  },
  computed: {
    showNotifIOsApp() {
      return this.$store.getters['settings/notifIOsApp']
    }
  },
  methods: {
    sendShowedScholarshipIDs(scholarships) {
      if(!scholarships || !scholarships.length) return;

      const ids = scholarships
        .map(scholarship => scholarship.scholarshipId)
        .filter(id => !!id);

      this.$store.dispatch('eligibilityCache/updateEligibilities', ids)
        .then(() => console.log("last shown scholarships ids are sent"))
        .catch(console.log);
    },
    setTabName() {
      const name = this.$route.hash === "#sent"
        ? SENT_SCHOLARSHIPS
        : NEW_SCHOLARSHIPS;

      this.setCurrentScholarships(name);
    },
    ...mapActions("scholarships", [
      "fetchScholarships",
      "fetchSentScholarships",
      "setCurrentScholarships"
    ]),
    ...mapActions("fset", ["getFsetData"])
  }
};
</script>