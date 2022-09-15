<template>
  <div class="survey-req-list">
    <div v-if="requirement.survey && requirement.survey.length"
      v-for="(survey, i) in requirement.survey" class="survey-req">
      <h3 class="survey-req__question">{{i+1}}. {{survey.question}}</h3>
      <p class="survey-req__description" v-html="survey.description"></p>
      <component :is="getComponent(survey.type)" class="survey-req__options"
        @application-status="changeApplicationStatus"
        @change="opt => emitSurvey(opt, survey.id)"
        :options="survey.options"
        :answers="getAnswer(survey.id)"/>
    </div>
  </div>
</template>

<script>
  import CheckBoxSet from "components/Pages/Scholarships/Requirement/Poll/Survey/CheckBoxSet.vue";
  import RadioButtonSet from "components/Pages/Scholarships/Requirement/Poll/Survey/RadioButtonSet.vue";

  export default {
    components: {
      CheckBoxSet,
      RadioButtonSet
    },
    props: {
      requirement: {type: Object, required: true},
      application: {type: Object, required: true},
      reqSetName: {type: String, required: true}
    },
    data() {
      return {
        survey: {
          survey: {}
        }
      }
    },
    created() {
      if(this.application && this.application.answers) {
        this.requirement.survey.forEach((survey, i) => {
          if(this.application.answers[i] && this.application.answers[i].options
          && this.application.answers[i].options.length) {
            this.survey.survey[survey.id] = this.application.answers[i].options;
          }
        })
      }

      this.$store.commit('scholarships/setInitialApplicationState');
    },
    methods: {
      getComponent(surveyType) {
        if(surveyType === 'radio') return RadioButtonSet;
        if(surveyType === 'checkbox') return CheckBoxSet;
      },
      getAnswer(surveyId) {
        if(!this.survey.survey[surveyId]) return [];

        return this.survey.survey[surveyId];
      },
      buildSurvey(options, surveyId) {
        this.survey["survey"][surveyId] = options;

        return this.survey;
      },
      emitSurvey(opt, surveyId) {
        const surveys = this.buildSurvey(opt, surveyId);

        const shouldSend = this.requirement.survey.every(survey => {
          return surveys.survey[survey.id] && surveys.survey[survey.id].length;
        })

        if(shouldSend) this.$emit("change", surveys);
      },
      changeApplicationStatus() {
        this.$store.commit('scholarships/setApplicationStateLocaly', {
          id: this.requirement.id,
          setName: this.reqSetName
        })
      }
    }
  }
</script>

<style lang="scss">
  .survey-req {
    font-size: 15px;
    line-height: 22px;
    color: $mine-shaft;

    & + & {
      margin-top: 22px;
    }

    &__question {
      font-weight: 600;
    }

    &__description {
      color: $silver-chalice;
      margin-top: 5px;
    }

    &__options {
      margin-top: 12px;
    }

    &_checkboxes {
      margin-top: 18px;
    }
  }

  .survey-options {
    .mdc-form-field {
      align-items: flex-start;
    }

    .mdc-form-field +
    .mdc-form-field {
      margin-top: 15px;
    }

    .checkbox-basic +
    .checkbox-basic {
      margin-top: 8px;
    }

    .checkbox-basic__text {
      font-size: 15px;
      color: $mine-shaft;
      line-height: 1.4em;
    }

    .mdc-radio {
      margin-top: -6px;
    }

    .checkbox-basic__input:checked +
    label .checkbox-basic__checkbox:before {
      border-color: $turquoise;
    }
  }
</style>