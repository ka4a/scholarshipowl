<template>
  <section :class="['subscription-information', {'subscription-information_one-child': !endDate && !showButton}]">
    <h4 @click="$emit('click')" v-if="membership.name" class="ma-title subscription-information__package-name">{{ membership.name }}</h4>
    <subscription-remainder :membership="membership" />
    <Button v-if="showButton" class="subscription-information__button" :href="isMobile() ? '/upgrade-mobile' : ''" size="s"
      theme="orange" @click.native="ev => $emit('upgrade', ev)" label="upgrade" />
  </section>
</template>

<script>
  import SubscriptionRemainder from "components/Pages/MyAccount/SubscriptionRemainder.vue";
  import Button from "components/Common/Buttons/ButtonCustom.vue";
  // imports freeTrialEndDate, activeUntil, date formater
  import untilPackageHelpers from "components/Common/membership/helpers";
  import { isMobile } from "lib/utils/utils";

  export default {
    mixins:[untilPackageHelpers],
    components: {
      SubscriptionRemainder,
      Button
    },
    props: {
      membership: {type: Object, required: true}
    },
    data() {
      return {
        isMobile
      }
    },
    computed: {
      showButton() {
        return !this.membership.isMember || this.membership.isFreemium;
      }
    }
  }
</script>

<style lang="scss">
  $dark: #2f2f2f;
  $dark-lighter: #616161;
  $orange: #ff6634;
  $red: #ed5858;
  $green: #2fd9b3;

  $open-sans: 'Open Sans';

  .subscription-information {
    display: flex;

    @include breakpoint(max-width $m - 1px) {
      justify-content: space-between;
      align-items: center;
    }

    @include breakpoint($m) {
      flex-direction: column;
    }

    &_one-child {
      display: inline-flex;
    }

    &__package-name {
      cursor: pointer;
      text-decoration: underline;

      @include breakpoint($m) {
        text-align: right;
      }
    }

    &__button {
      width: 156px;

      @include breakpoint($m) {
        margin-top: 70px;
      }

      @include breakpoint($l) {
        margin-top: 94px;
        margin-left: auto;
      }
    }
  }
</style>