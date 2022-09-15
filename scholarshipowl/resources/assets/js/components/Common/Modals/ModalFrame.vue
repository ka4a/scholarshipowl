<template>
  <section class="modal-frame">
    <CloseCtrl @click.native="closeHandler" />
    <slot name="default" />
  </section>
</template>

<script>
  import CloseCtrl from "components/Common/Modals/CloseCtrl.vue";

  export default {
    components: {
      CloseCtrl
    },
    props: {
      closeHandler: {type: Function, required: true}
    }
  }
</script>

<style lang="scss">
  .modal-frame {
    @extend %modal-frame-basic;

    transform: translate(0, 15px);

    @include breakpoint($s) {
      transform: translate(0, 11.25%);
    }

    @include breakpoint($m) {
      transform: translate(0, 21%);
    }

    @include breakpoint($l) {
      transform: translate(0, 17.22%);
    }

    @include breakpoint($s) {
      max-width: 430px;
      padding: 25px 21px 35px 21px;
    }

    @include breakpoint($m) {
      max-width: 530px;
      padding: 20px 32px 35px 32px;
    }

    @include breakpoint($l) {
      max-width: 570px;
      padding: 30px 60px;
    }
  }
</style>