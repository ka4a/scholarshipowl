<template>
  <Checkbox class="special-eligib-req"
    :id="requirement.permanentTag"
    :name="requirement.id"
    :value="application ? application.val : false"
    @input="handleInput">
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
      requirement: {type: Object, required: true},
      application: {type: Object, required: true}
    },
    methods: {
      handleInput(val) {
        this.$emit('change', {
          val: Number(val)
        })
      }
    }
  }
</script>

<style lang="scss">
  .special-eligib-req {
    background-color: $selago;
    padding: 20px 20px 20px 25px;
    margin-bottom: 25px;

    .checkbox-basic__wrp {
      align-items: center;
    }

    .checkbox-basic__text {
      color: $mine-shaft;
      font-size: 13px;
      line-height: 1.2em;
    }

    .checkbox-basic__input:checked +
    label .checkbox-basic__checkbox:before {
      border-color: $turquoise;
    }
  }
</style>