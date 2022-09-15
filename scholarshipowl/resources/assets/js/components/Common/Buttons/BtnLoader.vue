<template>
  <img :class="['btn-loader', `btn-loader_${size}`]"
    src="~components/Common/Buttons/loader.svg" />
</template>

<script>
  export default {
    props: {
      size: {type: String, required: true, default: "m"}
    }
  }
</script>

<style lang="scss">
  .btn-loader {
    -webkit-animation:spin 1s linear infinite;
    -moz-animation:spin 1s linear infinite;
    animation:spin 1s linear infinite;

    @-moz-keyframes    spin { 100% { -moz-transform: rotate(360deg); } }
    @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
    @keyframes         spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

    &_m {
      width: 28px;
    }

    &_s {
      width: 18px;
    }
  }
</style>