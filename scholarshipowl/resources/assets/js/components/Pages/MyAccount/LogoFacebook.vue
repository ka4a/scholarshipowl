<template>
  <i :style="{backgroundColor, color, width, height}" class="facebook-logo icon icon-facebook"></i>
</template>

<script>
  export default {
    props: {
      color: {type: String, default: '#ffffff'},
      backgroundColor: {type: String, default: '#474a99'},
      width: {type: String, default: '31px'},
      height: {type: String, default: '31px'}
    }
  }
</script>

<style lang="scss">
  .facebook-logo {
    display: block;
    border-radius: 6px;
    position: relative;
    overflow: hidden;

    &:before {
      display: block;
      position: absolute;
      top: -0.14em;
      right: -0.21em;
      font-size: 54px;
      line-height: 54px;
    }
  }
</style>