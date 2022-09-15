<template>
  <div style="margin-left: -6px;" class="survey-options">
    <RadioButton v-for="(option, i) in options"
      :checked="i === answers[0]"
      :key="option"
      :name="'radio-' + randomString()"
      :id="option + randomString()"
      :label="option"
      :value="option"
      @change="val => radio(i)"/>
  </div>
</template>

<script>
  import Vue from "vue";
  import RadioButton from "components/Common/Input/Radio/InputRadio.vue";
  import { randomString } from "lib/utils/utils.js";

  export default {
    components: {
      RadioButton
    },
    mounted() {
      this.choosen[0] = this.answers[0];
    },
    props: {
      options: {type: Array, required: true},
      answers: {type: Array, default: []}
    },
    data() {
      return {
        choosen: [],
      }
    },
    methods: {
      randomString,
      radio(optIndex) {
        Vue.set(this.choosen, 0, optIndex);

        this.$emit("change", this.choosen);
      }
    }
  }
</script>