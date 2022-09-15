<template>
  <div class="user-avatar">
    <img src="./MaleUserAvatar.png" v-if="!gender || gender !== 'female'" />
    <img src="./FemaleUserAvatar.png" v-else />
  </div>
</template>

<script>
  export default {
    props: {
      gender: {type: String, required: true}
    }
  }
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  $grey: #e4eaf3;

  .user-avatar {
    min-width: 130px;
    min-height: 130px;
    width: 130px;
    height: 130px;
    background-color: $grey;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;

    > img {
      width: 62%;
    }

    @include breakpoint($m) {
      width: 158px;
      height: 158px;
      min-width: 158px;
      min-height: 158px;
    }

    @include breakpoint($l) {
      width: 202px;
      height: 202px;
      min-width: 202px;
      min-height: 202px;
    }
  }
</style>