<template>
  <div v-if="endDate" class="subscription-remainder">
    <p :class="['subscription-remainder__1', {di: inline}]">
      <span v-if="freeTrialEndDate">FREE Trial</span>
      <span v-else>Membership</span>
      <span>Active</span>
    </p>
    <p :class="['subscription-remainder__1', {di: inline}]">until {{date}}</p>
  </div>
</template>

<script>
  // imports freeTrialEndDate, activeUntil, date formater
  // TODO extract membership store from account store
  // To move all computed functions there
  // This import uses in UserDropDown.vue component
  import untilPackageHelpers from "components/Common/membership/helpers";

  export default {
    mixins:[untilPackageHelpers],
    props: {
      membership: {type: Object, required: true},
      inline: {type: Boolean, default: false}
    },
  }
</script>

<style lang="scss">
  $open-sans: 'Open Sans';
  $red: #ed5858;

  .subscription-remainder {
      font-family: $open-sans;
      color: $red;

      margin-top: 12px;

      @include breakpoint($s) {
        text-align: right;
      }

      &__1 {
        font-size: 14px;
        line-height: 1.3em;
      }
    }
</style>