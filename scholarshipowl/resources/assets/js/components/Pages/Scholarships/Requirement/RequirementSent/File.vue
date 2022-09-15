<template lang="html">
  <wrapper type="file">
    <information-line type="file" :title="title" />
    <a :href="path"><download-controller /></a>
  </wrapper>
</template>

<script>
import Wrapper from "components/Pages/Scholarships/Requirement/Common/RequirementFilledWrapper.vue";
import DownloadController from "components/Pages/Scholarships/Requirement/Controllers/Download.vue";
import InformationLine from "components/Pages/Scholarships/Requirement/Common/InformationLine.vue";

export default {
  components: {
    DownloadController,
    InformationLine,
    Wrapper
  },
  props: {
    application: {type: Object, required: true}
  },
  computed: {
    path() {
      return '/file/download/' + this.application.accountFile.id;
    },
    title() {
      return this.application.accountFile.realname || this.application.accountFile.filename;
    }
  }
};
</script>