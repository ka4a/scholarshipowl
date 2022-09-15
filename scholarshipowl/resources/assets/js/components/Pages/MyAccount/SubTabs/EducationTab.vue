<template>
  <div class="education-sub-tab my-account-form">
    <ValidationProvider name="schoolLevel" ref="schoolLevel"
      :rules="{required: !!profile.schoolLevel}" :events="['close']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item"
        title="Current school level"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="schoolLevel"
          v-model="data.schoolLevel"
          :options="schoolLevels" />
      </input-item>
    </ValidationProvider>

   <ValidationProvider name="highschool" ref="highschool"
    :rules="{required: !!profile.highschool}" :events="['close']">
    <input-item slot-scope="{ errors, validate, reset }"
      class="my-account-form__item"
      title="High school name"
      :error-message="errors[0]">
      <input-select-dinamic
        @open="reset()"
        :error="!!errors.length"
        name="highschool"
        v-model="data.highschool"
        placeholder="High school name" />
    </input-item>
  </ValidationProvider>

  <input-item
      class="my-account-form__item"
      title="High school address 1">
    <input-text
        name="highschoolAddress1"
        placeholder="Address, city, state, zip"
        autocomplete="street-address address-line1"
        v-model="data.highschoolAddress1"/>
    </input-item>

    <input-item
      class="my-account-form__item"
      title="High school address 2">
      <input-text
        name="highschoolAddress2"
        placeholder="Address, city, state, zip"
        autocomplete="street-address address-line2"
        v-model="data.highschoolAddress2"/>
    </input-item>

    <ValidationProvider name="highSchoolGraduationDate" ref="highSchoolGraduationDate"
      :rules="{required: !!profile.highSchoolGraduationDate}" :events="['close']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="High school graduation date"
        :error-message="errors[0]">
        <input-date
          @open="applyAfter(100, reset)"
          type="tel"
          name="highSchoolGraduationDate"
          :error="!!errors.length"
          v-model="data.highSchoolGraduationDate"
          :show-day="false"
          :yearStart="now.getFullYear() - 69"
          :yearStop="now.getFullYear() + 9" />
      </input-item>
    </ValidationProvider>

   <ValidationProvider name="enrolled" ref="enrolled"
    :rules="{required: !!profile.schoollevel}">
    <input-item slot-scope="{ errors }"
      class="my-account-form__item"
      title="Enrolled in college"
      :error-message="errors[0]">
      <input-radio-list
        name="enrolled"
        :error="!!errors.length"
        v-model="data.enrolled"
        :list="yesOrNo"/>
    </input-item>
  </ValidationProvider>

  <template v-if="data.enrolled">
    <ValidationProvider v-if="Number(data.enrolled.value)"
     name="university" ref="university"
    :rules="{required: !!profile.university}">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item my-account-form__college-name"
        title="College name"
        :error-message="errors[0]">
        <input-select-dinamic
          @open="reset()"
          :error="!!errors.length"
          name="university"
          v-model="data.university"
          placeholder="College name" />
      </input-item>
    </ValidationProvider>
    <ValidationProvider v-else
      name="universities" ref="universities"
      :rules="{required: !!profile.universities, array_min: 3}"
      :events="['none']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item my-account-form__college-name"
        title="Potential college names"
        :error-message="errors[0]">
        <input-select-group
          @open="reset()"
          placeholder="College name"
          name="university"
          :error="!!errors.length"
          v-model="data.universities"
          :min="3" :max="5"/>
      </input-item>
    </ValidationProvider>
  </template>

  <ValidationProvider name="enrollmentDate" ref="enrollmentDate"
    :rules="{required: !!profile.enrollmentDate}" :events="['close']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      title="College enrollment date"
      :error-message="errors[0]">
      <input-date
        @open="applyAfter(100, reset)"
        type="tel"
        name="enrollmentDate"
        :error="!!errors.length"
        v-model="data.enrollmentDate"
        :show-day="false"
        :yearStart="now.getFullYear() - 6"
        :yearStop="now.getFullYear() + 14" />
    </input-item>
  </ValidationProvider>

  <input-item
    class="my-account-form__item"
    title="College address 1">
    <input-text
      name="universityAddress1"
      placeholder="Address, city, state, zip"
      autocomplete="street-address address-line3"
      v-model="data.universityAddress1"/>
    </input-item>

    <input-item
      class="my-account-form__item"
      title="College address 2">
      <input-text
        name="universityAddress2"
        placeholder="Address, city, state, zip"
        autocomplete="street-address address-line3"
        v-model="data.universityAddress2"/>
    </input-item>

  <ValidationProvider name="graduationDate" ref="graduationDate"
      :rules="{required: !!profile.graduationDate}" :events="['close']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="College graduation date"
        :error-message="errors[0]">
        <input-date
          @open="applyAfter(100, reset)"
          type="tel"
          name="graduationDate"
          :error="!!errors.length"
          v-model="data.graduationDate"
          :show-day="false"
          :yearStart="now.getFullYear() - 69"
          :yearStop="now.getFullYear() + 31" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="degree" ref="degree"
      :rules="{required: !!profile.degree}" :events="['close']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item"
        title="Field of study (Major)"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="degree"
          :options="degrees"
          v-model="data.degree" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="degreeType" ref="degreeType"
      :rules="{required: !!profile.degreeType}" :events="['close']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item"
        title="Degree type"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="degreeType"
          :options="degreeTypes"
          v-model="data.degreeType" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="gpa" ref="gpa"
      :rules="{required: !!profile.gpa}" :events="['close']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="GPA"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="gpa"
          :options="gpas"
          v-model="data.gpa" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="careerGoal" ref="careerGoal"
      :rules="{required: !!profile.careerGoal}" :events="['close']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="Career goal"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :options="careerGoals"
          :error="!!errors.length"
          name="careerGoal"
          v-model="data.careerGoal" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="studyOnline" ref="studyOnline"
      :rules="{required: !!profile.studyOnline}">
      <input-item slot-scope="{ errors }"
        class="my-account-form__item"
        title="Interested in studying online"
        :error-message="errors[0]">
        <input-radio-list
          name="studyOnline"
          :error="!!errors.length"
          v-model="data.studyOnline"
          :list="studyOnline"/>
      </input-item>
    </ValidationProvider>

    <ValidationProvider v-if="!isUSA" name="studyCountries" ref="studyCountries"
      :events="['close', 'blur']" :rules="{required: !!profile.studyCountry1, array_max: 5}">
      <input-item slot-scope="{ errors, reset, validate }"
        class="my-account-form__item"
        title="Where do you want to study?"
        :error-message="errors[0]">
        <input-multi-select
          @max-reached="() => {
            validate(); applyAfter(2000, reset);
          }"
          @open="reset"
          @close="validate"
          v-model="data.studyCountries"
          :error="!!errors.length"
          placeholder="Enter up to 5 countries"
          name="studyCountries"
          :min="1" :max="5"
          :options="countries" />
      </input-item>
    </ValidationProvider>

    <Button class="my-account-form__submit" @click.native="submit" theme="orange" size="l" label="SAVE CHANGES" />
  </div>
</template>

<script>
import MultiSelect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";
import { mapGetters } from"vuex";
import { AccountResource, UpdateUserProfile } from "resource.js";
import InputSelectBasic from "components/Common/Input/Select/InputSelectBase.vue";
import InputDate from "components/Common/Input/Select/InputDate.vue";
import InputSelectDinamic from "components/Common/Input/Select/InputSelectDinamic.vue";
import InputSelectGroup from "components/Common/Input/Select/InputSelectGroup.vue";
import InputRadio from "components/Common/Input/Radio/InputRadio.vue";
import InputRadioList from "components/Common/Input/Radio/InputRadioList.vue";
import Button from "components/Common/Buttons/ButtonCustom.vue";
import Title from "components/Common/Typography/Title.vue";
import InputText from "components/Common/Input/Text/InputTextBasic.vue";
import InputItem from "components/Common/Input/InputItem.vue"
import InputMultiSelect from "components/Common/Input/Select/InputMultiSelect.vue";
import { validator } from "components/Pages/MyAccount/SubTabs/validator"; // TODO Move it to common mixins
import { formatZip } from "lib/utils/format";
import { focusOnError } from "lib/utils/dom.js";
import { toLabelValueObj, stringToLabelValueObj, booleanToYesNoList, yymmddToDate,
  strArrayToLableValueObj, studyCountries as profileStudyCountries} from "components/Pages/MyAccount/SubTabs/initialInputDataFormaters"

const initValuesFileds = {
  schoolLevel: null,
  highschool: null,
  highschoolAddress1: '',
  highschoolAddress2: '',
  highSchoolGraduationDate: null,
  enrolled: null,
  enrollmentDate: null,
  university: null,
  universities: null,
  universityAddress1: '',
  universityAddress2: '',
  graduationDate: null,
  degree: null,
  degreeType: null,
  gpa: null,
  careerGoal: null,
  studyOnline: null
}

const fields = Object.keys(initValuesFileds);

export default {
  mixins: [validator],
  components: {
    MultiSelect,
    InputSelectBasic,
    InputDate,
    InputSelectDinamic,
    InputRadio,
    InputRadioList,
    Button,
    Title,
    InputText,
    InputItem,
    InputSelectGroup,
    InputMultiSelect,
    ValidationProvider
  },
  mounted() {
    this.$store.dispatch(
      "options/load", [
        "countries",
        "profileTypes",
        "gpas",
        "degrees",
        "degreeTypes",
        "careerGoals",
        "schoolLevels",
        "studyOnline",
        "studyCountries"
      ]
    );
  },
  created() {
    let profile = this.profile;

    let data = {
      schoolLevel               : toLabelValueObj(profile.schoolLevel),
      highschool                : stringToLabelValueObj(profile.highschool),
      highschoolAddress1        : profile.highschoolAddress1,
      highschoolAddress2        : profile.highschoolAddress2,
      highSchoolGraduationDate  : yymmddToDate(profile.highschoolGraduationYear, profile.highschoolGraduationMonth),
      enrolled                  : booleanToYesNoList(profile.enrolled),
      enrollmentDate            : yymmddToDate(profile.enrollmentYear, profile.enrollmentMonth),
      university                : stringToLabelValueObj(profile.universities.slice(0,1)[0]),
      universities              : profile.universities,
      universityAddress1        : profile.universityAddress1,
      universityAddress2        : profile.universityAddress2,
      graduationDate            : yymmddToDate(profile.graduationYear, profile.graduationMonth),
      degree                    : toLabelValueObj(profile.degree),
      degreeType                : toLabelValueObj(profile.degreeType),
      gpa                       : stringToLabelValueObj(profile.gpa),
      careerGoal                : toLabelValueObj(profile.careerGoal),
      studyOnline               : stringToLabelValueObj(profile.studyOnline)
    }

    if(!this.isUSA) {
      data["studyCountries"] = profileStudyCountries(profile);
    }

    Object.assign(this.data, data);
  },
  props: {
    profile: {type: Object, required: true},
    submiting: {type: Boolean, default: false},
    validationErrors: {type: Object, required: true}
  },
  watch: {
    validationErrors(errors) {
      if(!errors) return;

      Object.keys(errors).forEach(key => this.errors.add({
        field: key,
        msg: errors[key][0]
      }));
    }
  },
  data() {
    return {
      formater: formatZip,
      test: '',
      data: {
        ...initValuesFileds
      }
    }
  },
  computed: {
    ...mapGetters({
      isUSA:                "account/isUSA",
      countries:            "options/countries",
      yesOrNo:              "options/yesOrNo",
      label:                "options/label",
      option:               "options/option",
      profileTypes:         "options/profileTypes",
      gpas:                 "options/gpas",
      degrees:              "options/degrees",
      degreeTypes:          "options/degreeTypes",
      careerGoals:          "options/careerGoals",
      schoolLevels:         "options/schoolLevels",
      studyOnline:          "options/studyOnline",
      studyCountries:       "options/studyCountries"
    }),
    now() {
      return new Date();
    },
  },
  methods: {
    applyAfter(time, callback) {
      if(!time || !callback) return;

      setTimeout(callback, time);
    },
    submit() {
      if(this.submiting) return;

      this.validateAll().then(valid => {
        if(!valid) {
          this.scrollToError();
          return;
        }

        let data = {};

        fields.forEach(key => {
          if(this.data[key] && typeof this.data[key] !== 'object') {
            data[key] = this.data[key];
            return;
          }

          if(this.data[key] !== null) {
            if(this.data[key].value) {
              data[key] = this.data[key].value;
              return;
            }
          }
        })

        if(this.data.highschool) {
          data['highschool'] = this.data.highschool.label;
        }

        if(this.data.enrollmentDate) {
          data['enrollmentMonth'] = this.data.enrollmentDate.getMonth() + 1;
          data['enrollmentYear'] = this.data.enrollmentDate.getFullYear();
        }

        if(this.data.graduationDate) {
          data['graduationMonth'] = this.data.graduationDate.getMonth() + 1;
          data['graduationYear'] = this.data.graduationDate.getFullYear();
        }

        if(this.data.highSchoolGraduationDate) {
          data['highschoolGraduationMonth'] = this.data.highSchoolGraduationDate.getMonth() + 1;
          data['highschoolGraduationYear'] = this.data.highSchoolGraduationDate.getFullYear();
        }

        if(this.data.highschool) {
          data['highschool'] = this.data.highschool.label;
        }

        if(this.data.enrolled !== null) {
          let enrolled = Number(this.data.enrolled.value);

          data['enrolled'] = enrolled;

          if(enrolled && data['university']) {
            data['university'] = this.data.university.label;
          }

          if(!enrolled && this.data.universities && this.data.universities.length) {
            delete data['university'];
            data['universities'] = this.data.universities;
          }
        }

        if(!this.isUSA && this.data.studyCountries && this.data.studyCountries.length) {
          data["studyCountries"] = this.data.studyCountries.map(o => o.value)
        }

        if(!Object.keys(data).length) return;

        this.$emit('submit', data);
      })
    }
  }
};
</script>

<style lang="scss">
  $open-sans: 'Open Sans';
  $dark-grey-blue: #354c6d;
  $blue-more-light: rgba(148, 171, 206, 0.5);
  $grey: #555555;
  $grey-darker: #616161;
  $grey-lighter: #c4c4c4;
  $blue: #708fe7;
  $pale-sky-blue: #cee2ff;
  $white: white;
  $red: #ed5858;

  %input-text {
    font-family: $open-sans;
    font-size: 14px;
    line-height: 1.2em;
    color: $grey;
  }

  .input-title {
    font-family: $open-sans;
    font-size: 12px;
    line-height: 1.2em;
    font-weight: bold;
    color: $dark-grey-blue;
  }

  .text-input {
    @extend %input-text;
    border-radius: 2px;
    padding: 13px 15px;
    border: 1px solid #e8e8e8;
    background: $white;
    width: 100%;
    box-sizing: border-box;

    @include breakpoint($l) {
      padding: 16px 15px;
    }

    &_error {
      border-color: $red;

      &::placeholder {
        color: $red;
      }
    }

    &:focus {
      outline: none;
      border-color: $blue;
    }

    &:disabled {
      color: $blue-more-light;
      border-color: #e8e8e8;
    }
  }

  .my-account-form {
    $multiselect: 'multiselect';
    $multiselect-set-fm: 'multiselect-set-fm';
    $radio-fm: 'radio-fm';

    display: grid;
    grid-template-columns: 1fr;
    grid-column-gap: 28px;
    grid-row-gap: 12px;
    box-sizing: border-box;
    max-width: 904px;
    width: 100%;
    margin-right: auto;
    margin-left: auto;

    @include breakpoint($s) {
      grid-row-gap: 15px;
    }

    @include breakpoint($m) {
      grid-row-gap: 20px;
      grid-template-columns: 1fr 1fr;
    }

    @include breakpoint($l) {
      grid-column-gap: 38px;
    }

    &__email {
      grid-column: 1 / -1;
      @include breakpoint($m) {
        width: 48%;
      }
    }

    &__study-countries {
      grid-column: 1 / -1;
    }

    &__submit {
      grid-column: 1 / -1;
    }

    .#{$multiselect-set-fm} {
      display: flex;

      &__item {
        &_month,
        &_day {
          flex: 1 1 27%;
          min-width: 27.3%;
        }

        &_day,
        &_year {
          margin-left: 2px;
        }
      }
    }

    &__tag,
    &__select,
    &__input {
      margin-top: 5px;
    }

    &__tag {
      .multiselect__select {
        display: none;
      }

      .multiselect__tags {
        padding: 2px 30px 7px 15px;
      }

      &_empty {
        .multiselect__tags {
          padding-top: 7px;
        }
      }
    }

    &__radio {
      margin-top: 18px;

      &-error {
        .#{$radio-fm}__radio {
          border-color: $red !important;
        }
      }
    }

    &__error {
      font-family: $open-sans;
      font-size: 11px;
      color: $red;
      line-height: 1.2em;
      margin-top: 5px;
    }

    &__submit {
      width: 100%;
      margin-top: 13px;
      margin-left: auto;
      margin-right: auto;

      height: 65px;
      line-height: 65px;

      @include breakpoint($m) {
        margin-top: 18px;
        max-width: 424px;
      }
    }
  }
</style>