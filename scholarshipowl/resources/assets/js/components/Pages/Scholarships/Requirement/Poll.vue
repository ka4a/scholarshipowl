<template>
  <component :is="currentComponent"
    :requirement="requirement"
    :application="application"
    :reqSetName="reqSetName"
    @change="data => $emit('save', data)" />
</template>

<script>
  import { REQ_TYPES } from "store/scholarships";
  import Survey from "components/Pages/Scholarships/Requirement/Poll/Survey.vue";
  import SpecialEligibility from "components/Pages/Scholarships/Requirement/Poll/SpecialEligibility.vue";

  export default {
    components: {
      Survey,
      SpecialEligibility
    },
    props: {
      requirement: {type: Object, required: true},
      application: {type: Object, default: null},
      reqSetName: {type: String}
    },
    computed: {
      currentComponent() {
        switch(this.requirement.type) {
          case REQ_TYPES.SURVEY:
            return Survey;
          default:
            return SpecialEligibility;
        }
      }
    }
  }
</script>

<style lang="scss">
  .survey-req-list {
    margin-bottom: 24px;
  }
</style>