<template lang="html">
  <wrapper type="input">
    <input class="supper-input" v-if="isOpen" ref="input" type="text"
      :value="application.text" @change="$refs['input'].value = application.text">
    <information-line v-else type="input" :title="application.text" />
    <view-controller @click.native="isOpen = !isOpen" />
  </wrapper>
</template>

<script>
import Wrapper from "components/Pages/Scholarships/Requirement/Common/RequirementFilledWrapper.vue";
import ViewController from "components/Pages/Scholarships/Requirement/Controllers/View.vue";
import InformationLine from "components/Pages/Scholarships/Requirement/Common/InformationLine.vue";

export default {
  components: {
    ViewController,
    InformationLine,
    Wrapper
  },
  props: {
    application: {type: Object, required: true}
  },
  data() {
    return {
      isOpen: false
    };
  }
};
</script>

<style lang="scss">
  .supper-input {
    font-size: 12px;
    background: #FFFFFF;
    border: 1px solid #708FE7;
    box-sizing: border-box;
    border-radius: 2px;
    height: 40px;
    padding-left: 15px;
    padding-right: 15px;
    -webkit-flex-grow: 1;
    -moz-flex-grow: 1;
    -ms-flex-grow: 1;
    flex-grow: 1;
    display: block;
    outline: none;
  }
</style>