<template>
  <div class="content-requirement">
    <p v-if="isFile" @click="$emit('upload')" class="content-requirement__ctrl left-ctrl">
      <i class="icon icon-upload"></i><span class="text">upload</span>
    </p>
    <p v-if="isFile && isText" class="content-requirement__ctrl or">or</p>
    <p v-if="isText" @click="$emit('write')" class="content-requirement__ctrl right-ctrl">
      <i class="icon icon-write"></i><span class="text">write</span>
    </p>
  </div>
</template>
<script>
export default {
  name: "RequirementContent",
  props: {
    isText: { type: Boolean, default: false },
    isFile: { type: Boolean, default: false },
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

.content-requirement {
  $grey: #616161;
  $blue: #708FE7;

  font-size: 12px;
  height: 50px;
  text-align: center;
  border: 1px dashed #D8D8D8;
  border-radius: 2px;
  box-sizing: border-box;

  @include flexbox();
  @include justify-content(center);
  @include align-items(center);

  &__ctrl {
    cursor: pointer;
  }

  .or {
    color: $grey;
  }
  .text {
    color: $grey;
    text-transform: capitalize;
  }
  .icon {
    color: $blue;
    font-size: 16px;
    margin-right: 8px;
    vertical-align: middle;
  }

  .icon-upload {
    font-size: 22px;
  }

  .or,
  .right-ctrl {
    margin-left: 15px;
  }

  @include breakpoint($s) {
    font-size: 14px;
    height: 60px;

    .right-ctrl {
      margin-left: 20px;
    }

    .right-ctrl,
    .or {
      margin-left: 25px;
    }

    .icon {
      margin-right: 10px;
      font-size: 20px;
    }

    .icon-upload {
      font-size: 28px;
    }
  }

  @include breakpoint($m) {
    height: 70px;
  }
}
</style>
