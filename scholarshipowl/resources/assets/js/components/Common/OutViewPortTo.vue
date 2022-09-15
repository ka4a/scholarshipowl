<template lang="html">
  <button>
    <span class="icon icon-arrow-down-double arrow-down-double"></span>
  </button>
</template>

<script>
import { scrollTo } from '../../lib/utils/dom'

export default {
  props: {
    anchorElement: { required: true }
  }
}
</script>

<style lang="css">
</style>
