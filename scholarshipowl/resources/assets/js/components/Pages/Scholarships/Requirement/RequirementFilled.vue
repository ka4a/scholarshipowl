<template lang="html">
  <wrapper :type="type">
    <information-line :type="type" :title="label" />
    <edit-controller :class="{'ctrl-wrp-left' : type === 'input'}" @click.native="$emit('edit')" />
    <delete-controller @click.native="$emit('delete')" />
  </wrapper>
</template>

<script>
import Wrapper from "components/Pages/Scholarships/Requirement/Common/RequirementFilledWrapper.vue";
import EditController from "components/Pages/Scholarships/Requirement/Controllers/Edit.vue";
import DeleteController from "components/Pages/Scholarships/Requirement/Controllers/Delete.vue";
import InformationLine from "components/Pages/Scholarships/Requirement/Common/InformationLine.vue";

export default {
  name: "FilledApplicationRequirement",
  components: {
    EditController,
    DeleteController,
    InformationLine,
    Wrapper
  },
  props: {
    label: { type: String, required: true },
    type: { type: String, required: true },
  },
};
</script>


