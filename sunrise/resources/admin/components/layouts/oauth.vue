<template>
  <div class="oauth-layout">
    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
export default {

}
</script>
<style lang="scss">
@import "../../scss/default";
.oauth-layout {
  display: flex;
  height: 100%;
  > .container {
    margin: auto;
  }
}
</style>
