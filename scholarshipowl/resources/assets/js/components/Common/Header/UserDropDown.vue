<template>
	<div class="user-information" v-if="isOpen">
      <h3 class="user-information__user-name">{{ profile.fullName }}</h3>
      <div style="margin-top: 4px; line-height: 0.9em">
        <p class="user-information__period">{{ packageName }} membership</p>
        <p class="user-information__package" v-if="membership && membership.freeTrial">FREE TRIAL</p>
        <p  v-if="membership && membership.subscriptionId" class="user-information__deadline">
          <span v-if="membership.is_scholarships_unlimited">unlimited</span>
          <span v-if="endDate">Until: {{ date }}</span>
        </p>
      </div>

      <div style="position: relative">
        <p class="user-information__completeness">{{ profile.completeness.toString() }}%</p>
        <progress-indicator class="information-progress-bar" :completeness="profile.completeness" />
      </div>

      <ul class="user-information-links">
        <li><a class="user-information-links__link" href="/my-account"><i class="icon icon-user-profile"></i>my profile</a></li>
        <li><a class="user-information-links__link" href="/logout"><i class="icon icon-user-logout"></i>logout</a></li>
        <li>
          <a @click.prevent="toPayment" class="user-information-links__upgrade">upgrade</a>
        </li>
      </ul>
    </div>
</template>

<script>
import ProgressIndicator from "components/Pages/Own/ProgressIndicator.vue";
import { toPayment } from "components/Common/Payment/helpers";

// imports freeTrialEndDate, activeUntil, date formater
// TODO extract membership store from account store
// To move all computed functions there
// This import uses in SubscriptionRemainder.vue component
import untilPackageHelpers from "components/Common/membership/helpers";

export default {
  mixins:[untilPackageHelpers],
  components: {
    ProgressIndicator
  },
  props: {
    isOpen: {type: Boolean, required: true},
    profile: {type: Object, required: true},
    membership: {type: Object, required: true}
  },
  computed: {
    packageName() {
      return !this.membership ? "" : this.membership.subscriptionId ? this.membership.name : "free";
    }
  },
  methods: {
    toPayment
  }
};
</script>

<style lang="scss">
 .user-information {
    position: absolute;
    top: 74px; right: 5px;
    width: 196px;

    border-radius: 4px;
    background: $white;
    box-shadow: 0px 8px 30px rgba(50, 50, 93, 0.15);
    box-sizing: border-box;

    padding: 20px;

    font-family: 'Open Sans';
    font-size: 16px;

    color: $mine-shaft;

    @include breakpoint($l) {
      right: 35px;
    }

    &:before {
      content: '';
      position: absolute;
      top: -15px;
      right: 34px;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 0 18px 16px 18px;
      border-color: transparent transparent $white transparent;

      @include breakpoint($s) {
        right: 106px;
      }

      @include breakpoint($m) {
        right: 20px;
      }

      @include breakpoint($l) {
        right: 50px;
      }

    }

    &__user-name {
      font-family: 'Open Sans';
      font-size: 16px;
      font-weight: 600;
      line-height: 1.375em;
      margin: 0;
      padding: 0;
    }

    &__period {
      font-size: 10px;
      font-family: 'Open Sans';
      color: $outrageous-orange;
      text-transform: uppercase;
      margin: 0;
    }

    &__package {
      font-size: 12px;
      font-family: 'Open Sans';
      color: $outrageous-orange;
      text-transform: uppercase;

      margin: 4px 0 0 0;
    }

    &__deadline {
      font-family: 'Open Sans';
      font-size: 10px;
      margin: 0;
      text-transform: uppercase;
    }

    &__completeness {
      font-size: 10px;
      position: absolute;
      right: 0; top: -15px;
    }
  }

  .information-progress-bar {
    margin-top: 19px;
    height: 4px;

    .progress-indicator-line {
      height: 4px;
    }
  }

  .user-information-links {
    margin: 0;
    padding: 0;
    list-style: none;

    margin-top: 12px;
    border-top: 1px solid $concrete;
    padding-top: 11px;

    &__link {
      font-family: 'Open Sans';
      font-size: 14px;
      padding: 3px 0;
      color: $mine-shaft;
      font-weight: 600;
      text-transform: uppercase;
      display: block;

      &:hover {
        text-decoration: none;
        color: lighten($mine-shaft, 10);
      }

      .icon {
        color: $silver;
        padding-right: 10px;
        font-size: 18px;
        vertical-align: middle;
      }

      .icon-user-logout {
        margin-left: -2px;
      }
    }

    &__upgrade {
      margin-top: 17px;

      font-size: 12px;
      text-transform: uppercase;
      color: $white;
      text-align: center;
      font-weight: 600;
      line-height: 30px;
      cursor: pointer;

      width: 100%;
      height: 30px;
      background-color: $outrageous-orange;
      border-radius: 2px;

      display: block;

      &:hover {
        color: #FFF;
        text-decoration: none;
        background-color: darken($outrageous-orange, 5)
      }
    }
  }
</style>