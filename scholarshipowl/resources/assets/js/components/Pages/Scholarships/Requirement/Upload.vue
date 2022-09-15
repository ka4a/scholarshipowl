<template>
  <div class="block-sealer">
    <requirement-content v-if="!application && !saving"
      :isFile="requirement.type === REQ_TYPES.FILE || requirement.allowFile || requirement.type === REQ_TYPES.IMAGE"
      :isText="requirement.type === REQ_TYPES.TEXT"
      @write="$emit('write')"
      @upload="$emit('upload')" />
    <card-loader v-if="saving"
      class="requirement-loader"
      message="A bit of patience, almost done..." />
    <requirement-filled v-if="application && !saving"
      :label="label"
      :type="requirement.type"
      @edit="isFile ? $emit('upload') : $emit('write')"
      @delete="$emit('delete')" />
    <error-message style="margin-top: 10px" v-if="errors && errors.length" :errors="errors" />
  </div>
</template>

<script>
  import { REQ_TYPES }      from "store/scholarships";
  import RequirementContent from "components/Pages/Scholarships/Requirement/RequirementContent.vue";
  import CardLoader         from "components/Pages/Scholarships/CardLoader/CardLoader.vue";
  import ErrorMessage       from "components/Pages/Scholarships/Requirement/Common/ErrorMessage.vue";
  import RequirementFilled  from "components/Pages/Scholarships/Requirement/RequirementFilled.vue";

  export default {
    components: {
      RequirementContent,
      ErrorMessage,
      RequirementFilled,
      CardLoader,
    },
    props: {
      requirement: {type: Object, required: true},
      application: {type: Object, default: null},
      saving: {type: Boolean, default: false},
      errors: {type: Array, default: []}
    },
    data() {
      return {
        REQ_TYPES,
        isInputModalOpen: false
      }
    },
    computed: {
      isFile() {
        return !!this.application.accountFile
      },
      label() {
        return this.isFile
          ? this.application.accountFile.realname
          : this.requirement.title;
      }
    }
  }
</script>