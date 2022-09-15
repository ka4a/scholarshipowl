<template>
  <input-select-base
    v-bind="$attrs"
    v-on="$listeners"
    @input="inputHolder"
    :searchable="true"
    :name="name"
    :value="value"
    :allow-empty="true"
    :multiple="true" />
</template>

<script>
  import InputSelectBase from "components/Common/Input/Select/InputSelectBase.vue";

  export default {
    components: {
      InputSelectBase
    },
    props: {
      value: {type: Object, required: true},
      min: {type: Number, required: true},
      max: {type: Number, required: true},
      name: {type: String},

    },
    data() {
      return {
        disabled: false
      }
    },
    methods: {
      inputHolder(val) {
        if(val && val.length >= this.max) {
          this.$emit('max-reached', val);

          setTimeout(() => {
            val.splice(this.max, val.length);
          }, 2000)
        }

        this.$emit('input', val);
      }
    }
  }
</script>
