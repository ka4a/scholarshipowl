<template>
  <Checkbox class="special-eligib-req" :name="requirement.id" :value="true" :disabled="true">
      <span slot="label">{{requirement.text}}</span>
  </Checkbox>
</template>

<script>
  import Checkbox from "components/Common/CheckBoxes/CheckBoxBasic.vue";

  export default {
    components: {
      Checkbox
    },
    props: {
      requirement: {type: Object, required: true}
    }
  }
</script>