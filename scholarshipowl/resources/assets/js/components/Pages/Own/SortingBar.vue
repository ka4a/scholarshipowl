<template>
  <div>
    <toggle-controller
      ref="sortingToggleBtn"
      :is-disable="isDisable"
      :is-open="isOpen"
      label-name="sort by"
      @click.native.prevent="clickHolder" />
    <sorting-panel
      :name-space="nameSpace"
      :unit-list="sortingSettings"
      v-if="isOpen"
      v-click-outside="{
        exclude: ['sortingToggleBtn'],
        handler: 'closeSortingPane'
      }"
      @reset="clickHolder" />
  </div>
</template>

<script>
import ToggleController from "components/Pages/MailBox/ToggleController.vue"
import SortingPanel from "components/Pages/Own/Sorting/SortingPanel.vue"

export default {
  props: {
    sortingSettings: {type: Array, required: true},
    nameSpace: {type: String, required: true},
    isDisable: {type: Boolean, default: false}
  },
  components: {
    ToggleController,
    SortingPanel
  },
  data() {
    return {
      isOpen: false
    }
  },
  methods: {
    clickHolder() {
      if(this.isDisable) return;

      this.isOpen = !this.isOpen
    },
    closeSortingPane() {
      this.isOpen = false
    }
  }
};
</script>