<template>
  <button class="btn-out-line"><slot>{{text}}</slot></button>
</template>

<script>
  export default {
    props: {
      text: { type: String, required: true }
    }
  }
</script>

<style lang="scss">
  $blue: #597CE1;

  .btn-out-line {
    font-family: "Open Sans";
    font-size: 14px;
    text-align: center;
    letter-spacing: 0.035px;
    color: $blue;

    line-height: 45px;

    height: 45px;
    width: 100%;
    border: 1px solid $blue;
    box-sizing: border-box;
    border-radius: 2px;

    transition: all 200ms cubic-bezier(0.4, 0.0, 0.2, 1);

    @include breakpoint($m) {
      font-size: 16px;
      height: 50px;
      line-height: 50px;
    }

    &:hover {
      border-color: darken($blue, 7);
      color: darken($blue, 7);
    }

    &:focus {
      box-shadow: 0 1px 3px 0 rgba(168, 168, 168, 0.5);
    }
  }
</style>