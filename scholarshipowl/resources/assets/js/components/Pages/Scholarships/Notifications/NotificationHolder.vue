<template lang="html">
  <section class="notification-wrp scholarship-details__wrp">
    <template v-if="notification.closable">
      <a v-if="xl || xxl" @click.prevent="closeHolder" href="#" class="icon icon-clear notification-wrp__close"></a>
      <back-button v-else @click.native="closeHolder" class="notification-wrp__back" />
    </template>

    <back-button v-if="!(xl || xxl) && notification.showBackButton"
      class="notification-wrp__back" @click.native="actionHolder(notification.controller.action)" />

    <div style="overflow: auto">
      <notification class="notification-wrp__notification" @action="actionHolder"
        @track="mixpanelTrack" :name="notificationName" :notification="notification" />

      <freemium v-if="freemium && notificationName === 'freemium-success' || notificationName === 'freemium-no-credits'"
        :upgrade-button-text="content.upgradeBlockLinkUpgrade"
        :vip-button-text="content.upgradeBlockLinkVip"
        :upgrade-block-text="content.upgradeBlockText">
        <p slot="counter" class="notification-wrp__counter">
          <span v-if="notificationName !== 'no-credits'">{{ freemiumCredits - credits }} of {{ freemiumCredits }}</span>
          <span v-html="content.noCreditsContent" v-else></span>
        </p>
      </freemium>
    </div>
  </section>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { NEW_SCHOLARSHIPS, FAVORITES_SCHOLARSHIPS } from "store/scholarships";
import { ROUTES, lastPathPartName } from "router.js";

import mixpanel from "lib/mixpanel";
import Notification from "./Notification.vue";
import Freemium from "./Freemium.vue";
import notifications from "./notifications";
import BackButton from "components/Pages/Own/BackButton.vue"

export default {
  components: {
    Notification,
    Freemium,
    BackButton
  },
  mounted() {
    var element = document.querySelector(".right-container");
    element.scrollTop = 0;
  },
  computed: {
    ...mapState({
      credits:          state => state.account.membership.credits,
      freemiumCredits:  state => state.account.membership.freemiumCredits,
    }),
    ...mapGetters({
      freemium: "account/isFreemium",
      xl: "screen/xl",
      xxl: "screen/xxl",
      content: "fset/scholarshipsPageContent",
    }),
    notifications() {
      notifications["freemium-success"].message = this.content.applicationSentTitle;
      notifications["freemium-success"].notification = this.content.applicationSentDescription;

      notifications["freemium-no-credits"].message = this.content.noCreditsTitle;
      notifications["freemium-no-credits"].notification = this.content.noCreditsDescription;

      return notifications;
    },
    notificationName() {
      return lastPathPartName(this.$route.path)
    },
    notification() {
      if(this.notifications.hasOwnProperty(this.notificationName)) {
        return this.notifications[this.notificationName];
      }
    },
  },
  methods: {
    ...mapActions("scholarships", [
      "setCurrentScholarships"
    ]),
    actionHolder(action) {
      if(!action) throw Error("Please provide action name");

      if(action === "LIST") {
        this.$emit('global', { ev:'show-details', value: false });
        return;
      }

      if(action === "DETAILS") {
        this.$emit('global', { ev:'apply' });
        return;
      }

      if(action === NEW_SCHOLARSHIPS) {
        this.setCurrentScholarships(NEW_SCHOLARSHIPS);
        this.$emit('global', { ev:'show-details', value: false });
        return;
      }

      if(action === FAVORITES_SCHOLARSHIPS) {
        this.setCurrentScholarships(FAVORITES_SCHOLARSHIPS);
        this.$emit('global', { ev:'show-details', value: false });
        return;
      }
    },
    mixpanelTrack() {
      if(this.notificationName === "won") {
        mixpanel.track("Claim [Won Screen]");
      }

      if(this.notificationName === "missed") {
        mixpanel.track("Continue Applying [Missed]");
      }

      if(this.notificationName === "awarded") {
        mixpanel.track("Continue Applying [Missed]");
      }

      if(this.notificationName === "winner-chosen") {
        mixpanel.track("See Who Won [Winner Chosen]");
      }
    },
    closeHolder() {
      this.$router.push(ROUTES.SCHOLARSHIPS);

      this.$emit('close');
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/functions';

$dark: #2F2F2F;
$white: #FFFFFF;
$orange: #FF6633;
$grey: #C2C2C2;
$blue: #708FE7;

.margin-align-center {
  margin-left: auto;
  margin-right: auto;
}

.notification-wrp {
  text-align: center;
  @include flexbox();
  @include flex-direction(column);
  @include justify-content(center);
  @include flex(1 1 auto);
  height: 100%;
  position: relative;

  &__counter {
    color: $grey;
      font-size: 16px;
      line-height: lhem(22, 16);
      margin-top: 17px;

      @include breakpoint($m) {
        margin-top: 19px;
      }
  }

  &__back {
    position: absolute;
    top: 0;
  }

  &__close {
    font-size: 33px;
    color: $blue;
    position: absolute;
    top: 0; right: 0;

    @include breakpoint($xl) {
      top: -27px;
    }
  }
}

.notification-wrp_no-matches {
  .notification__notif {
    @include breakpoint($l $xl - 1px) {
      max-width: 390px;
      margin-left: auto;
      margin-right: auto;
    }
  }
}

.notification-wrp_failure {
  .notification__message {
    @include breakpoint(max-width $s - 1px) {
      span {
        display: block;
      }
    }
  }
}

</style>