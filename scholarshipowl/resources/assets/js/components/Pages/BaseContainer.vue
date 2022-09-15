<template>
  <div class="base-container">
    <scholarships-header class="base-container__header" />
      <div class="base-container__main-container">
        <slot>Please, provide some inner content</slot>
      </div>
      <scholarships-footer class="base-container__footer" />
      <slot name="login" />
      <Modal />
    </div>
</template>

<script>
import ScholarshipsHeader from "components/Common/Header/Header.vue";
import ScholarshipsFooter from "components/Common/Footer/MiniFooter.vue";
import Modal from "components/Common/Modals/Modal.vue";

export default {
  components: {
    ScholarshipsHeader,
    ScholarshipsFooter,
    Modal
  }
};
</script>

<style lang="scss">
  a {
    text-decoration: none;
  }

  html, body {
    width: 100%;
    background-color: white;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  }

  .base-container {
    font-family: 'Open Sans', sans-serif;
    display: flex;
    height: 100%;
    flex-direction: column;
    color: #333333;

    &__header {
      flex: 1 0 auto;
      width: auto !important;
    }

    &__main-container {
      position: relative;
      overflow: hidden;
      flex: 1 1 auto;
      overflow-y: hidden;
      display: flex;
    }

    &__footer {
      flex: 1 0 auto;
    }
  }

  ::-webkit-scrollbar {
      -webkit-appearance: none;
      width: 7px;
  }

  ::-webkit-scrollbar-thumb {
      border-radius: 4px;
      background-color: rgba(0,0,0,.5);
      -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
  }
</style>