<template lang="html">
  <header class="bp-l__header">
    <logo class="logo" :is-short="false" @click.native.prevent />
  </header>
</template>

<script>
import Logo from "components/Common/Header/Logo.vue";

export default {
  components: {
    Logo
  },
};
</script>

<style lang="scss">
  .bp-l__header {
    .logo {
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 10px;
      padding-bottom: 10px;
    }
  }
</style>
