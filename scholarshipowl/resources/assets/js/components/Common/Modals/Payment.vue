<template>
  <ModalFrame :show="show" @opened="$emit('opened')"
    @closed="$emit('closed', closedEventPlayload)">
    <div class="modal-payment">
      <CloseCtrl @click.native="$emit('ready-to-close')" />
      <div class="modal-payment__top">
        <Summary
          v-if="package"
          class="modal-payment__summary"
          :package="package" />
        <Payment class="modal-payment__payment"
          :isModalShow="show"
          :package="package"
          @hide="hideHandler" />
      </div>
      <SecureText class="modal-payment__secure-text" />
      <Explanation />
    </div>
  </ModalFrame>
</template>

<script>
  import Summary from "components/Common/Modals/Payment/Summary.vue"
  import Payment from "components/Common/Modals/Payment/Payment.vue";
  import Explanation from "components/Common/Modals/Payment/Explanation.vue";
  import ModalFrame from "components/Common/Modals/Payment/ModalFrame.vue";
  import CloseCtrl from "components/Common/Modals/CloseCtrl.vue";
  import SecureText from "components/Common/Modals/Payment/Payment/SecureText.vue";

  export default {
    components: {
      Summary,
      Payment,
      Explanation,
      ModalFrame,
      CloseCtrl,
      SecureText
    },
    props: {
      show: {type: Boolean, required: true, default: false},
      package: {type: Object, required: true},
    },
    data() {
      return {
        closedEventPlayload: null
      }
    },
    methods: {
      hideHandler(playload) {
        if(playload) this.closedEventPlayload = playload;

        this.$emit('ready-to-close');
      }
    }
  }
</script>

<style lang="scss">
  $open-sans: "Open Sans";
  .modal-payment {
    padding: 0;
    margin: 0;
    max-width: 100%;
    position: relative;

    font-family: $font-family-basic;
    color: $mine-shaft;
    background-color: $white;

    @include breakpoint(max-width $m - 1px) {
      height: 100%;
      -webkit-overflow-scrolling: touch;
      overflow-y: hidden;
    }

    @include breakpoint($m) {
      margin-left: 30px;
      margin-right: 30px;
    }

    @include breakpoint(980px) {
      max-width: 920px;
      margin-left: auto;
      margin-right: auto;
    }

    &__top {
      padding-bottom: 15px;

      @include breakpoint($m) {
        display: flex;
        padding: 50px 30px 15px 40px;
      }

      @include breakpoint($l) {
        padding: 40px 35px 15px 40px;
      }
    }

    &__summary {
      @include breakpoint($m) {
        flex: 40.5% 1 1;
        box-sizing: border-box;
        order: 1;

        display: flex;
        flex-direction: column;
        justify-content: center;
      }
    }

    &__payment {
      @include breakpoint(max-width $m - 1px) {
        padding-left: 15px;
        padding-right: 15px;
      }

      @include breakpoint($m) {
        flex: 76% 1 1;
      }

      @include breakpoint($l) {
        flex: 87% 1 1;
      }
    }

    &__secure-text {
      margin: 0 15px 15px 15px;

      @include breakpoint($m) {
        margin-left: 40px;
        margin-right: 30px;
      }
    }
  }
</style>