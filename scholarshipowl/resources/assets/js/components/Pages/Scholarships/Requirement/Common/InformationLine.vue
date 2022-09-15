<template>
  <span :class="['information-line', {'information-line_input': type === 'input'}]">
    <i v-if="type !== 'input'" class="icon icon-doc"></i>
    <span :class="['information-line-label', {'information-line-label_input': type === 'input'}]">{{ title }}</span>
  </span>
</template>

<script>
export default {
  props: {
    type: {type: String, required: true},
    title: {type: String, required: true}
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

$blue: #708FE7;
$blue-light: #F3F8FF;
$black: #2F2F2F;

 .information-line {
    margin-right: auto;

    &_input {
      height: 40px;
      line-height: 40px;
      display: block;
      background-color: $blue-light;
      padding-left: 15px;
      box-sizing: border-box;
      min-width: 64%;
      margin-right: 0;
      @include flex-grow(1);

      @include breakpoint($s) {
        min-width: 69%;
      }

      @include breakpoint($m) {
        min-width: 74%;
      }

      @include breakpoint($l) {
        height: 43px;
        line-height: 43px;
        min-width: 71%;
      }

      @include breakpoint($xl) {
        min-width: 77%;
      }
    }

  .icon-doc {
    margin-right: 6px;

    @include breakpoint($s) {
      margin-right: 15px;
    }
  }

  .icon {
    color: $blue;
    font-size: 14px;

    @include breakpoint($s) {
      font-size: 16px;
    }
  }
 }

.information-line-label {
  color: $black;
  font-size: 12px;
  max-width: 70px;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.2em;
  height: 1.2em;

  @include breakpoint($s) {
    max-width: 125px;
  }

  @include breakpoint($m) {
    max-width: 250px;
  }

  @include breakpoint($l) {
    max-width: 370px;
  }

  &_input {
    color: $blue;
    max-width: 145px;

    @include breakpoint($m) {
      max-width: 260px;
    }

    @include breakpoint($xl) {
      max-width: 363px;
    }
  }
}
</style>
