<template>
  <h1 class="sowl-logo">
    <a :href="clickable ? '/' : '#'" class="sowl-logo__link">
    <span class="sowl-logo__img"><i v-if="isShort" class="icon icon-r"></i></span>
    <span v-if="!isShort" class="sowl-logo__text"><strong>Scholarship</strong>Owl<i class="icon icon-r"></i></span>
    </a>
  </h1>
</template>

<script>
export default {
  props: {
    isShort: {type: Boolean, default: true},
    clickable: {type: Boolean, default: true}
  }
};
</script>

<style lang="scss">
.sowl-logo {
  // reset
    margin: 0;
    padding: 0 14px 0 0;
    display: inline-block;
    font-family: "Open Sans";
    font-size: 20px;
    color: #000;
    font-weight: 300;

    &__link {
      color: #000;
      max-width: 213px;
      display: flex;
      align-items: center;
    &:focus,
      &:hover {
        text-decoration: none;
        color: #000;
      }
    }

  &__img {
    display: inline-block;
    width: 43px; height: 40px;
    background: url('./sowl-logo.svg') top left no-repeat;
    // background: url('./sowl-santa-logo.png') top left no-repeat;
    background-size: 100%;
    position: relative;

    .icon-r {
      font-size: 8px;
      position: absolute;
      line-height: 1em;
      bottom: 0;
      right: -12px;
    }
  }

  &__text {
    margin-left: 8px;
    position: relative;
    font-family: "Open Sans";

    .icon-r {
      font-size: 9px;
        line-height: 1em;
        position: absolute;
        top: 2px;
        right: -12px;
    }

    strong {
      font-weight: 700;
    }
  }
}
</style>