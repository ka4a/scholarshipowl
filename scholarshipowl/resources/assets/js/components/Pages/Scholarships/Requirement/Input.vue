<template>
  <div class="block-sealer">
    <RequirementInput v-if="!saving && !isReqApplied"
      :placeholder="placeholder"
      :inputText="application && application.text ? application.text : ''"
      @save="text => { $emit('input', {text, cb: () => (isReqApplied = true)})}" />
    <CardLoader v-if="saving"
      class="requirement-loader"
      message="A bit of patience, almost done..."/>
    <RequirementFilled v-if="!saving && isReqApplied"
      :label="label"
      :type="requirement.type"
      @edit="isReqApplied = false"
      @delete="$emit('delete', () => (isReqApplied = false));" />
    <ErrorMessage style="margin-top: 10px" v-if="errors.length" :errors="errors" />
  </div>
</template>

<script>
  // TODO fix deletion input requirement
  import RequirementInput   from "components/Pages/Scholarships/Requirement/RequirementInput.vue";
  import RequirementFilled  from "components/Pages/Scholarships/Requirement/RequirementFilled.vue";
  import CardLoader         from "components/Pages/Scholarships/CardLoader/CardLoader.vue";
  import ErrorMessage       from "components/Pages/Scholarships/Requirement/Common/ErrorMessage.vue";

  export default {
    components: {
      RequirementInput,
      RequirementFilled,
      CardLoader,
      ErrorMessage
    },
    props: {
      saving: {type: Boolean, default: false},
      errors: {type: Array, default: []},
      application: {type: Object, default: null},
      requirement: {type: Object, default: null}
    },
    data() {
      return {
        isReqApplied: false
      }
    },
    computed: {
      // TODO compare props application and requirement for all
      // requirement types
      label() {
        if(!this.application) return "";

        return this.application.text || this.requirement.title;
      },
      placeholder() {
        return this.requirement.name.toLowerCase() === 'input'
          ? 'Enter text'
          : 'http://example.com'
      }
    }
  }
</script>