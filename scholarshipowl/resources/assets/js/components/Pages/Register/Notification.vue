<template lang="html">
  <div :class="{ [className] : true, 'ok': state, 'err': !state }" v-show="typeof state !== 'undefined'">
    <i :class="className + '__icon'"></i>
    <p :class="className + '__text'" v-html="content"></p>
  </div>
</template>

<script>
export default {
  name:"Notification",
  props: {
    className:  { type: String, default: "fm-accordion-not"},
    success:    { required: true},
    failed:     { required: true},
    state:      { type: Boolean, default: null},
  },
  computed: {
    content: function() {
      return this.state ? this.success : this.failed;
    }
  }
};
</script>

<style lang="css">
</style>
