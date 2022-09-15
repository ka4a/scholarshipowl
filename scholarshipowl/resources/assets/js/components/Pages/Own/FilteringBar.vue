<template>
  <div>
    <toggle-controller
      ref="filterToggleBtn"
      label-name="filters"
      :is-open="isOpen"
      @click.native.prevent="clickHolder" />
    <filters
      @filter="(ev) => { clickHolder(); $emit('filter', ev); }"
      v-if="isOpen"
      v-click-outside="{
        exclude: ['filterToggleBtn'],
        handler: 'closeFilterPane'
      }"
      :name-space="nameSpace"
      class="filters__filter-panel" />
  </div>
</template>

<script>
import ToggleController from "components/Pages/MailBox/ToggleController.vue"
import Filters from "components/Pages/Own/Filters.vue"

export default {
  components: {
    ToggleController,
    Filters
  },
  props: {
    nameSpace: {type:String, required: true},
  },
  data() {
    return {
      isOpen: false
    }
  },
  methods: {
    clickHolder() {
      this.isOpen = !this.isOpen;
    },
    closeFilterPane() {
      this.isOpen = false
    }
  }
};
</script>