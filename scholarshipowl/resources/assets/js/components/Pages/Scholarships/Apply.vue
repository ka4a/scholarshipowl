<template lang="html">
  <section class="apply">
    <p v-if="this.isFreemiumMVP && !this.isReady"
      class="apply__notif">Requirements missing to apply</p>
    <button @click="apply()" :class="classes">
      <span v-if="applying">applying</span>
      <span v-else-if="isFreemiumMVP || isReady || (isFreemium && isCreditsLimitRiched && isReady)">apply</span>
      <span v-else>Complete Requirements To Apply</span>
    </button>
    <a v-if="isFreemiumMVP" class="apply__external" target="_blank" @click="track(APPLY_EXTERNAL_BTN_CLK)"
      :href="scholarship.externalUrl">Apply on external website</a>
  </section>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import mixpanel from "lib/mixpanel";
import {APPLY_OWL_BTN_CLK, APPLY_EXTERNAL_BTN_CLK} from "lib/mixpanel";
import { toPayment } from "components/Common/Payment/helpers";
import { SCHOLARSHIP_STATUS } from "lib/utils/filter";
import { ROUTES } from "router.js";

const ReadyStatus = SCHOLARSHIP_STATUS.READY_TO_SUBMIT;

export default {
  props: {
    scholarship: { type: Object, require: true }
  },
  computed: {
    ...mapState({
      credits: state => state.account.membership.credits,
    }),
    ...mapGetters({
      isFreemium:     "account/isFreemium",
      isFreemiumMVP:  "account/isFreemiumMVP"
    }),
    isCreditsLimitRiched() {
      return this.credits === 0;
    },
    classes() {
      return [
        'apply-btn',
        {'apply-btn_ready': this.isReady},
        {'apply-btn_limit': this.isFreemium && this.isCreditsLimitRiched && this.isReady},
        {'apply-btn_freemium-mvp': this.isFreemiumMVP && !this.isReady}
      ]
    },
    isReady() {
      return this.scholarship.application.status === ReadyStatus;
    },
  },
  data: function() {
    return {
      APPLY_EXTERNAL_BTN_CLK,
      applying: false
    };
  },
  methods: {
    track(eventName) {
      mixpanel.track(eventName);
    },
    apply() {
      if (!this.isReady || this.applying) {
        return;
      }

      this.track(APPLY_OWL_BTN_CLK);

      if(this.isFreemiumMVP && this.isCreditsLimitRiched) {
        toPayment();

        return;
      }

      if(this.isFreemium && this.isCreditsLimitRiched) {
        this.$router.push(`${ROUTES.SCHOLARSHIPS}/${ROUTES.FREEMIUM_NO_CREDITS}`);
        return;
      }

      this.applying = true;

      return this.$store.dispatch("scholarships/apply", this.scholarship)
        .then(response => {
          this.applying = false;

          if(!response) return;

          let route = this.isFreemium
            ? `${ROUTES.SCHOLARSHIPS}/${ROUTES.FREEMIUM_SUCCESS}`
            : `${ROUTES.SCHOLARSHIPS}/${ROUTES.SUCCESS}`

          this.$router.push(route);
        })
        .catch(response => {
          this.applying = false;

          if(!response) return;

          if (response.status === 409) {
            if(this.isFreemium) {
              this.$router.push(`${ROUTES.SCHOLARSHIPS}/${ROUTES.FREEMIUM_NO_CREDITS}`);
              return;
            }

            if(this.isFreemiumMVP) {
              toPayment();
            }
          }

          this.$router.push(`${ROUTES.SCHOLARSHIPS}/${ROUTES.FAILURE}`);

          setTimeout(() => {
            this.$router.push(ROUTES.SCHOLARSHIPS);
          }, 4000);
        });
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';
@import 'main/meta/palette';

$small-devices: 600px;
$blue: $cornflower-blue;
$blue-light: $zircon;
$green: $turquoise;
$orange: $flesh;

.apply {
  margin-top: 15px;
  margin-bottom: 10px;

  @include breakpoint($s) {
    margin-top: 20px;
    margin-bottom: 0;
  }

  @include breakpoint($l) {
    margin-top: 30px;
  }

  &__notif {
    color: $silver-chalice;
    font-size: 15px;
    text-align: center;
    margin-bottom: 10px;

    @include breakpoint($l) {
      font-size: 14px;
    }
  }

  &__external {
    color: $havelock-blue;
    font-size: 15px;
    text-decoration: underline;
    text-align: center;
    display: block;
    margin-top: 6px;

    @include breakpoint($l) {
      margin-top: 16px;
    }
  }
}

.apply-btn {
  font-weight: 300;
  font-size: 14px;
  text-align: center;
  color: $blue;
  text-transform: uppercase;
  line-height: 50px;

  height: 50px;
  width: 100%;
  background-color: $blue-light;
  border-radius: 2px;

  @include breakpoint($s) {
    font-size: 18px;
    height: 60px;
    line-height: 60px;
  }

  @include breakpoint($m) {
    font-size: 23px;
    height: 80px;
    line-height: 80px;
  }

  &_ready {
    background-color: $green;
    color: white;
    font-weight: 700;
  }

  &_limit {
    background-color: $orange;
    color: white;
    font-weight: 700;
  }

  &_freemium-mvp {
    background-color: $alto;
    color: $silver;
    font-weight: 700;
    cursor: not-allowed;
  }
}
</style>
