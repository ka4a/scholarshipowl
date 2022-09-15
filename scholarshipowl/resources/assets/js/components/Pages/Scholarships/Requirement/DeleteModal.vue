<template>
  <section class="delete-modal">
    <div class="delete-modal__wrp">
      <h5 class="delete-modal__title">
        <i class="icon icon-delete"></i>
        <span>Delete {{ requirementName.toLowerCase() }} ?</span>
      </h5>
      <p class="delete-modal__paragraph">Are you sure you want to delete your {{ requirementName.toLowerCase() }}?</p>
      <div class="delete-modal__btn-wrp">
        <button @click="$emit('close')" class="delete-modal__btn">cancel</button>
        <button @click="$emit('delete')" class="delete-modal__btn red-btn">delete</button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    requirementName: { type: String, required: true}
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/functions';

$dark: #2F2F2F;
$blue: #4E8EEC;
$blue-light: rgba(154, 180, 255, 0.29);
$red: #EB5757;
$white: #FFFFFF;

.delete-modal {
  width: 100%; height: 100%;
  top: 0; left: 0;
  position: fixed;
  z-index: 9999;
  background-color: rgba(53, 76, 109, 0.25);

  &__wrp {
    width: 250px;
    box-sizing: border-box;
    background-color: $white;
    padding: 15px;
    position: absolute;
    top: 50%; transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    left: 0; right: 0;
    margin-left: auto; margin-right: auto;
      border: 1px solid rgba(154, 180, 255, 0.29);
    box-shadow: 0px 4px 12px rgba(34, 38, 43, 0.37);

      @include breakpoint($s) {
        width: 320px;
        padding: 20px;
      }

      @include breakpoint($m) {
        width: 530px;
        padding: 30px 28px;
      }

      @include breakpoint($l) {
        width: 477px;
        padding: 30px;
      }

      @include breakpoint($xl) {
        width: 606px;
      }
  }

  &__title {
    font-size: 16px;
    line-height: lhem(20, 16);
    color: $dark;
    font-weight: 700;
    @include flexbox();

    @include breakpoint($s) {
      font-size: 18px;
      line-height: lhem(20, 18);
    }

    .icon {
      margin-right: 10px;
    }

    .icon-delete {
      width: 20px;
      height: 20px;
      display: inline-block;
      position: relative;
      border-radius: 4px;
      background-color: $red;

      &:before {
        content: '';
        position: absolute;
        display: block;
        height: 4px;
        width: 14px;
        background-color: $white;
        top: 0; left: 0; bottom: 0; right: 0;
        margin: auto;
      }
    }
  }

  &__paragraph {
    color: $dark;
    font-size: 12px;
    line-height: lhem(20, 12);
    margin-top: 19px;

    @include breakpoint($s) {
      font-size: 14px;
      line-height: lhem(20, 14);
      margin-top: 22px;
      text-align: center;
    }
  }

  &__btn-wrp {
    margin-top: 15px;
    max-width: 215px;
    margin-left: auto;
    margin-right: auto;
    @include flexbox();
    @include justify-content(space-between);

    @include breakpoint($s) {
      margin-top: 20px;
      max-width: 256px;
    }

    @include breakpoint($m) {
      margin-top: 30px;
      max-width: 266px;
    }
  }

  &__btn {
    font-size: 12px;
    color: $white;
    line-height: 20px;
    text-align: center;
    text-transform: uppercase;
    font-weight: 700;

    width: 100px;
    height: 30px;
    background-color: $blue;
    border-radius: 2px;

    @include breakpoint($s) {
      width: 118px;
    }

    &:hover {
      background-color: darken($blue, 5);
    }

    &.red-btn {
      background-color: $red;
      &:hover {
        background-color: darken($red, 5);
      }
    }
  }
}
</style>