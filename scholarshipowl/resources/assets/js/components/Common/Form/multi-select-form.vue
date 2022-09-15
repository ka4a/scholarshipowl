<template lang="html">
  <p>
    <v-select multiple v-model="selected" :options="countries"></v-select>
  </p>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex'
import vSelect from 'vue-select'

export default {
  props: {
    step: {type: String, required: true},
    name: {type: String, required: true},
    maxItems: {type: String}
  },
  data() {
    return {
      selected: null,
      // mock for appearance, will remove it
      countries: [
        {label: 'one', value: 'one'},
        {label: 'two', value: 'two'},
        {label: 'three', value: 'three'},
        {label: 'forth', value: 'forth'},
        {label: 'five', value: 'five'},
        {label: 'six', value: 'siz'},
      ]
    }
  },
  components: {
    vSelect
  },
  watch: {
    selected: function(newValue, oldValue) {
      if(newValue.length > this.max) {
        this.selected.pop();
      }

      let values = this.selected.map(item => item.value);

      this.setValue({
        value: values,
        name: this.name,
        regStep: this.step
      })
    }
  },
  computed: {
    max: function() {
      return Number(this.maxItems)
    },
    ...mapGetters('options', ['countries'])
  },
  methods: {
    ...mapActions('registration', ['setValue'])
  }
}
</script>

<style lang="css">
</style>
