<template lang="html">
  <div :class="['filled-requirement', {'input-requirement': type === 'input', 'basic-requirement': type !== 'input'}]">
    <slot>please provide child elements</slot>
  </div>
</template>

<script>
export default {
  name: "RequirementFilledWrapper",
  props: {
    type: {type: String, required: true}
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

$blue: #708FE7;
$blue-light: #F3F8FF;
$black: #2F2F2F;

.filled-requirement {
  @include flexbox();
  @include justify-content(center);
  @include align-items(center);

  &.basic-requirement {
    background-color: $blue-light;
    height: 50px;
    padding-left: 10px;
    padding-right: 10px;

    @include breakpoint($s) {
      height: 60px;
      padding-left: 15px;
      padding-right: 15px;
    }

    @include breakpoint($m) {
      height: 70px;
      padding-left: 25px;
      padding-right: 25px;
    }
  }

  &.input-requirement {
    height: 40px;

    @include breakpoint($l) {
      height: 43px;
      padding-right: 20px;
    }
  }

  .information-line {
    @include flex(1 1 auto);
  }

  .ctrl-wrp-left {
    @include breakpoint($s $m - 1px) {
      margin-left: 20px;
    }
  }
}
</style>