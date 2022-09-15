<template>
  <ModalFrame class="modal-sent-message" :closeHandler="() => $emit('hide')">
    <section>
      <Img class="modal-sent-message__img" />
      <ModalContent v-bind="content" />
    </section>
  </ModalFrame>
</template>

<script>
  import ModalFrame from "components/Common/Modals/ModalFrame.vue";
  import ModalContent from "components/Common/Modals/ModalContent.vue";
  import Img from "./img/SentMessage.vue";

  export default {
    components: {
      ModalFrame,
      ModalContent,
      Img
    },
    props: {
      content: {type: Object, required: true},
    }
  }
</script>

<style lang="scss">
  .modal-sent-message {
    margin-left: auto;
    margin-right: auto;
    max-width: 274px;
    border-radius: 5px;
    padding: 55px 15px 50px 15px;

    @include breakpoint($l) {
      max-width: 314px;
      padding: 23px 15px 32px 15px;
    }

    .modal-frame__ctrl {
      right: 15px;
      top: 15px;
    }

    &__img {
      margin-left: auto;
      margin-right: auto;
      display: block;

      @include breakpoint($l) {
        height: auto;
        width: 48px;
      }
    }

    .vue-modal-content__title {
      margin-top: 10px;
      margin-bottom: 7px;
      font-size: 18px;

      @include breakpoint($l) {
        margin-top: 0;
        margin-bottom: 5px;
      }
    }

    .vue-modal-content__paragraph {
      font-size: 15px;
    }
  }
</style>