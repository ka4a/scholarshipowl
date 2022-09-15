<template>
  <input-select
    label="label"
    track-by="value"
    v-bind="$attrs"
    v-on="$listeners"
    :placeholder="placeholder"
    :type="type"
    :value="value"
    :error="error"
    :name="name"/>
</template>

<script>
  import InputSelect from "components/Common/Input/Select/InputSelect.vue";

  export default {
    name: 'input-select-base',
    components: {
      InputSelect
    },
    props: {
      value:       {type: Object, required: true},
      name:        {type: String},
      error:       {type: Boolean, default: false},
      type:        {type: String},
      placeholder: {type: String}
    }
  }
</script>