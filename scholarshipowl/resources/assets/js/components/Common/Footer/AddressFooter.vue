<template lang="html">
  <div class="address-footer">
    <h5 class="address-footer__title">Scholarshipowl.com is operated by</h5>
    <address class="address-footer__information">
      <p class="address-footer__company-name">{{ companyDetails.companyName }}</p>
      <p v-if="companyDetails" class="address-footer__address">{{ companyDetails.address1 }}</p>
    </address>
    <address v-if="isContactPage">
      <p v-if="companyDetails && 'companyName2' in companyDetails"
        class="address-footer__company-name">{{ companyDetails.companyName2 }}</p>
      <p class="address-footer__address">{{ companyDetails.address2 }}</p>
    </address>
  </div>
</template>

<script>
export default {
  props: {
    companyDetails: {type: Object, required: true}
  },
  computed: {
    isContactPage() {
      return window.document.location.pathname.indexOf("/contact") !== -1;
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/_breakpoints.scss';

  %reset {
    margin: 0;
    padding: 0;
  }

  $footer-layout-color: #8094B0;

  .address-footer {
    address {
      line-height: 1.43em;
    }

    address + address {
      margin-top: 10px;
    }

    &__title {
      @extend %reset;
      font-size: 12px;
      font-weight: 400;
      line-height: 1.5em;
    }

    &__address {
      @extend %reset;
    }

    &__address-item {
      @extend %reset;
      font-size: 12px;
      line-height: 1.5em;
    }

    &__company-name {
      margin: 0;
    }

    @include breakpoint($s) {
      padding-left: 15px;
      padding-right: 15px;

      &__information {
        display: inline;
      }

      &__title {
        display: inline;
      }

      &__company-name {
        display: inline;
      }

      &__address {
        display: inline;
      }
    }

    @include breakpoint($m) {
      padding-left: 40px;
      padding-right: 40px;
    }

    @include breakpoint($xl) {
      address + address {
        margin-top: 20px;
      }

      &__company-name {
        display: block;
        margin: 0;
      }

      &__address {
        display: block;

      }
    }
  }
</style>
