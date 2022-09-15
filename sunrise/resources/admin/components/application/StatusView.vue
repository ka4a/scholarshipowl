<template>
  <span class="application-status-view" :class="{ [`is-${status.id}`]: true }">
    <i class="status-dot" />
    <span>{{ status.name }}</span>
  </span>
</template>
<script>
export default {
  props: {
    status: {
      type: Object,
      required: true,
    },
  },
};
</script>
<style lang="scss" scoped>
@import "~scss/variables.scss";
.application-status-view {
  // display: flex;
  // align-items: center;
  .status-dot {
    display: inline-block;
    height: 8px;
    width: 8px;
    border-radius: 50%;
    margin-right: 6px;
    background: #B6BECA;
  }
  &.is-accepted {
    .status-dot {
      background-color: #91BD7D;
    }
  }
  &.is-rejected {
    .status-dot {
      background-color: #D9374C;
    }
  }
}
</style>
