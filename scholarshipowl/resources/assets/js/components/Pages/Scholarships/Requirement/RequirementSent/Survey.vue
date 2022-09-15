<template>
  <div>
    <wrapper type="text">
      <information-line type="text" title="Survey answers" />
      <view-controller @click.native="isOpen = true" />
    </wrapper>
    <popup v-if="isOpen" :title="title" buttonText="close" @button="isOpen = false">
      <div slot="text" class="sent-survey">
        <template v-for="(answer, i) in application.answers">
          <h5 class="sent-survey__title">{{i + 1}}. {{answer.question}}</h5>
          <ul :class="['sent-survey__options', answer.type]">
            <li v-for="option in answer.options">{{requirement.survey[i].options[option]}}</li>
          </ul>
        </template>
      </div>
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
      title() {
        return this.requirement.type.charAt(0).toUpperCase() + this.requirement.type.slice(1);
      }
    }
  }
</script>

<style lang="scss">
  .sent-survey {
    font-size: 15px;
    line-height: 22px;

    &__title {
      font-weight: 600;
      margin-top: 15px;

      @include breakpoint($l) {
        margin-top: 22px;
      }
    }

    &__options {
      margin-top: 8px;
    }

    &__options.checkbox {
      list-style-type: disc;
      list-style-position: inside;
      margin-left: 15px;
    }
  }
</style>