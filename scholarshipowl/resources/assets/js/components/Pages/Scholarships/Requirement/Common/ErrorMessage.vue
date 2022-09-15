<template>
  <ul>
    <li v-for="error in errors" class="requirement-error">
      <i class="requirement-error__icon"></i>
      <p class="requirement-error__text">{{ error }}</p>
    </li>
  </ul>
</template>

<script>
  export default {
    props: {errors: {type: Array}}
  }
</script>

<style lang="scss">
.requirement-error {
  display: flex;
  align-items: top;
  margin-bottom: 15px;

  &__icon {
    margin-right: 10px;
    width: 16px;
    height: 16px;
    min-width: 16px;
    display: inline-block;
    background-color: $burnt-sienna;
    border-radius: 4px;
    text-align: center;
    line-height: 14px;

    &:before {
      content: '!';
      color: white;
      font-weight: 700;
      font-size: 13px;
    }

    @include breakpoint($s) {
      min-width: 20px;
      width: 20px;
      height: 20px;
      line-height: 20px;

      &:before {
        font-size: 16px;
      }
    }
  }

  &__text {
    color: $burnt-sienna;
    font-size: 14px;
    line-height: 1.4em;
  }

  .input-requirement {
    margin-top: 20px;

    @include breakpoint($s) {
      margin-top: 25px;
    }

    @include breakpoint($m) {
      margin-top: 30px;
    }
  }

  // modification near elements
  & + .input-requirement {
    .input-requirement__input,
    .input-requirement__btn {
      border: 1px solid $burnt-sienna;
    }
  }

  & + .content-requirement {
    border: 1px solid $burnt-sienna;
  }
}
</style>