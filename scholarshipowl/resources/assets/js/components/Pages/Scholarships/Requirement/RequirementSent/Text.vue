<template>
  <div>
    <wrapper type="text">
      <information-line type="text" :title="requirement.title" />
      <view-controller @click.native="isOpen = true" />
    </wrapper>
    <popup v-if="isOpen" :title="requirement.title" buttonText="close" @button="isOpen = false">
      <div slot="text" class="popup-text" v-if="application.text" v-html="formatedText"></div>
    </popup>
  </div>
</template>

<script>
import Wrapper from "components/Pages/Scholarships/Requirement/Common/RequirementFilledWrapper.vue";
import ViewController from "components/Pages/Scholarships/Requirement/Controllers/View.vue";
import InformationLine from "components/Pages/Scholarships/Requirement/Common/InformationLine.vue";
import Popup from "components/Pages/Scholarships/Requirement/Common/Popup.vue";

export default {
  components: {
    ViewController,
    InformationLine,
    Wrapper,
    Popup
  },
  props: {
    application: {type: Object, required: true},
    requirement: {type: Object, required: true}
  },
  data() {
    return {
      isOpen: false
    }
  },
  computed: {
    formatedText() {
      return this.application.text.replace(/(?:\r\n|\r|\n)/g, "<br>");
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  .popup-text {
    @include flex(1 1 auto);
    margin-top: 20px;
    overflow: auto;
  }
</style>