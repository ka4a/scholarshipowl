<template>
  <ModalFrame :closeHandler="() => $emit('hide')">
    <section class="vue-modal-promotion">
      <ModalContent :title="content.title"
      :text="content.text" :html="content.html" />
    </section>
  </ModalFrame>
</template>

<script>
  import ModalFrame from "components/Common/Modals/ModalFrame.vue";
  import ModalContent from "components/Common/Modals/ModalContent.vue";

  export default {
    components: {
      ModalFrame,
      ModalContent
    },
    props: {
      content: {type: Object, required: true},
    }
  }
</script>

<style lang="scss">
  .vue-modal-promotion {
    margin: 35px auto 30px auto;
    max-width: 368px;
  }
</style>