<template>
  <div class="survey-options survey-req_checkboxes">
    <Checkbox v-for="(option, i) in options"
      :value="!!choosen[i]"
      :key="i" :name="'checkbox-' + randomString()"
      @input="val => checkbox(val, i, option)">
        <span slot="label">{{option}}</span>
    </Checkbox>
  </div>
</template>

<script>
  import Vue from "vue";
  import Checkbox    from "components/Common/CheckBoxes/CheckBoxBasic.vue";
  import { randomString } from "lib/utils/utils.js";

  export default {
    components: {
      Checkbox
    },
    mounted() {
      if(this.answers.length) {
        this.choosen = this.answers.reduce((acc, answer) => {
          if(!this.options[answer]) return acc;

          acc[answer] = this.options[answer];
          return acc;
        }, {})
      }
    },
    props: {
      options: {type: Array, required: true},
      answers: {type: Array, default: []}
    },
    data() {
      return {
        choosen: {}
      }
    },
    methods: {
      randomString,
      checkbox(val, i, option) {
        if(val) {
          Vue.set(this.choosen, i, option);
        } else {
          Vue.delete(this.choosen, i);
        }

        const selected = Object.keys(this.choosen);

        if(selected.length) {
          this.$emit("change", selected);
        } else {
          //TODO it's a dirty huck, and should be removed in future.
          //it's here becouse lack of task requirement. Scholarship
          //state should be determinated from back-end side.

          this.$emit('application-status');
        }
      }
    }
  }
</script>