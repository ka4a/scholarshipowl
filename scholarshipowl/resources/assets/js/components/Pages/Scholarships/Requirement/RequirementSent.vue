<template lang="html">
  <div class="requirement-wrp">
    <component :is="component" :application="application" :requirement="requirement" />
  </div>
</template>
<script>
import { REQ_TYPES } from "store/scholarships";
import File from "components/Pages/Scholarships/Requirement/RequirementSent/File.vue";
import Input from "components/Pages/Scholarships/Requirement/RequirementSent/Input.vue";
import Text from "components/Pages/Scholarships/Requirement/RequirementSent/Text.vue";
import Special from "components/Pages/Scholarships/Requirement/RequirementSent/SpecialEligibility.vue";
import Survey from "components/Pages/Scholarships/Requirement/RequirementSent/Survey.vue"

export default {
  name: "FilledSentRequirement",
  components: {
    File,
    Input,
    Text,
    Special,
    Survey,
  },
  props: {
    requirement: { type: Object, required: true},
  },
  computed: {
    application() {
      return this.$store.getters["scholarships/getRequirementApplication"](this.requirement);
    },
    component() {
      switch(this.requirement.type) {
        case REQ_TYPES.INPUT:
          return Input
        case REQ_TYPES.TEXT:
          return this.application.accountFile
            ? File : Text;
        case REQ_TYPES.SURVEY:
          return Survey;
        case REQ_TYPES.SPECIAL_ELIGIBILITY:
          return Special;
        default:
          return File;
      }
    }
  }
};
</script>