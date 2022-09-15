<template>
  <div class="base-hor-indent form-wrp reg2-form">
    <section v-if="isMobile" class="accordion-register">
      <accordion-item
        title="I am a"
        ref="profileType"
        @open="open('profileType')">
        <input-radio-list
          name="profileType"
          :value="data.profileType"
          @input="val => input(val, 'profileType')"
          :list="profileTypes"/>
      </accordion-item>

      <accordion-item
        title="Birthdate"
        ref="dateOfBirth"
        @open="open('dateOfBirth')" >
        <input-date
          type="tel"
          name="dateOfBirth"
          :value="data.dateOfBirth"
          @input="val => dateValidate(val, 'dateOfBirth')"
          :yearStop="new Date().getFullYear() - 16" />
      </accordion-item>

      <accordion-item
        title="Citizenship"
        ref="citizenship"
        @open="open('citizenship')">
        <input-select-basic
          name="citizenship"
          :optionsLimit="300"
          :value="data.citizenship"
          @input="val => input(val, 'citizenship')"
          :options="citizenships" />
      </accordion-item>

      <accordion-item
        title="Ethnicity"
        ref="ethnicity"
        @open="open('ethnicity')">
        <input-select-basic
          name="ethnicity"
          :value="data.ethnicity"
          @input="val => input(val, 'ethnicity')"
          :options="ethnicities" />
      </accordion-item>

      <accordion-item
        title="Gender"
        ref="gender"
        @open="open('gender')">
        <input-radio-list
          :appear="(xs || s) ? 'vertical' : 'horizontal'"
          name="gender"
          :value="data.gender"
          @input="val => input(val, 'gender')"
          :list="genders"/>
      </accordion-item>

      <accordion-item
        title="Current school level"
        ref="schoolLevel"
        @open="open('schoolLevel')">
        <input-select-basic
          name="schoolLevel"
          :value="data.schoolLevel"
          @input="val => input(val, 'schoolLevel')"
          :options="schoolLevels" />
      </accordion-item>

      <accordion-item
        title="High school name"
        ref="highschool"
        @open="open('highschool')">
       <input-select-dinamic
          name="highschool"
          :value="data.highschool"
          @input="val => input(val, 'highschool')"
          placeholder="High school name" />
      </accordion-item>

      <accordion-item
        title="High school graduation date"
        ref="highschoolGraduationDate"
        @open="open('highschoolGraduationDate')">
        <input-date
          type="tel"
          name="highschoolGraduationDate"
          :value="data.highschoolGraduationDate"
          @input="val => dateValidate(val, 'highschoolGraduationDate')"
          :show-day="false"
          :yearStart="now.getFullYear() - 69"
          :yearStop="now.getFullYear() + 9" />
      </accordion-item>

      <accordion-item
        title="Enrolled in college"
        ref="enrolled"
        @open="open('enrolled')">
        <input-radio-list
          name="enrolled"
          :value="data.enrolled"
          @input="handleEnrolledChanges"
          :list="yesOrNo"/>
      </accordion-item>

      <accordion-item
        title="College enrollment date"
        ref="enrollmentDate"
        @open="open('enrollmentDate')">
        <input-date
          type="tel"
          name="enrollmentDate"
          :value="data.enrollmentDate"
          @input="val => dateValidate(val, 'enrollmentDate')"
          :show-day="false"
          :yearStart="now.getFullYear() - 6"
          :yearStop="now.getFullYear() + 14" />
      </accordion-item>

      <accordion-item
        :title="data.enrolled === null || data.enrolled.value ? 'College name' : 'Potential college names'"
        ref="universities"
        @open="open('universities')">
        <input-select-dinamic v-if="data.enrolled === null || data.enrolled.value"
          placeholder="College name"
          name="university"
          :value="university"
          @input="setUniversity" />
        <input-select-group v-else
          placeholder="College name"
          name="university"
          :value="data.universities"
          @input="val => universitiesValidate(val, 'universities')"
          :min="3" :max="5"/>
      </accordion-item>

      <accordion-item
        title="College graduation date"
        ref="collegeGraduationDate"
        @open="open('collegeGraduationDate')">
        <input-date
          type="tel"
          name="collegeGraduationDate"
          :value="data.collegeGraduationDate"
          @input="val => dateValidate(val, 'collegeGraduationDate')"
          :show-day="false"
          :yearStart="now.getFullYear() - 69"
          :yearStop="now.getFullYear() + 31" />
      </accordion-item>

      <accordion-item
        title="Field of study (Major)"
        ref="degree"
        @open="open('degree')">
        <input-select-basic
          name="degree"
          :value="data.degree"
          @input="val => input(val, 'degree')"
          :options="degrees" />
      </accordion-item>

      <accordion-item
        title="Degree type"
        ref="degreeType"
        @open="open('degreeType')">
        <input-select-basic
          name="degreeType"
          :value="data.degreeType"
          @input="val => input(val, 'degreeType')"
          :options="degreeTypes" />
      </accordion-item>

      <accordion-item
        title="GPA"
        ref="gpa"
        @open="open('gpa')">
        <input-select-basic
          name="gpa"
          :value="data.gpa"
          @input="val => input(val, 'gpa')"
          :options="gpas" />
      </accordion-item>

      <accordion-item
        title="Career goal"
        ref="careerGoal"
        @open="open('careerGoal')">
        <input-select-basic
          name="careerGoal"
          :options="careerGoals"
          :value="data.careerGoal"
          @input="val => input(val, 'careerGoal')" />
      </accordion-item>

      <accordion-item
        title="Interested in studying online"
        ref="studyOnline"
        @open="open('studyOnline')">
        <input-radio-list
          name="studyOnline"
          :value="data.studyOnline"
          @input="val => input(val, 'studyOnline')"
          :list="studyOnline"/>
      </accordion-item>

      <accordion-item
        title="Military affiliation"
        ref="militaryAffiliation"
        @open="open('militaryAffiliation')">
        <input-select-basic
          name="militaryAffiliation"
          @input="val => input(val, 'militaryAffiliation')"
          :value="data.militaryAffiliation"
          :options="militaryAffiliations" />
      </accordion-item>
    </section>
    <section v-else class="reg2-input-set">
      <ValidationProvider name="profileType" ref="profileType"
        :rules="{required: true}" :events="['blur']">
        <input-item slot-scope="{ errors, reset, valid }"
          class="my-account-form__item"
          title="I am a"
          :error-message="errors[0]">
          <input-radio-list
            :error="!!errors.length"
            name="profileType"
            v-model="data.profileType"
            :list="profileTypes"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="dateOfBirth" ref="dateOfBirth"
        :rules="{required: true, age: 16}" :events="['none']">
        <input-item slot-scope="{ errors, reset, valid, validate }"
          class="my-account-form__item"
          title="Birthdate"
          :error-message="errors[0]">
          <input-date
            @open="reset"
            @dirty="validate"
            type="tel"
            name="dateOfBirth"
            :error="!!errors.length"
            v-model="data.dateOfBirth"
            :yearStop="new Date().getFullYear() - 16" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="citizenship" ref="citizenship"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Citizenship"
          :error-message="errors[0]">
          <input-select-basic
            @open="reset"
            name="citizenship"
            :optionsLimit="300"
            :error="!!errors.length"
            v-model="data.citizenship"
            :options="citizenships" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="ethnicity" ref="ethnicity"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Ethnicity"
          :error-message="errors[0]">
          <input-select-basic
            @open="reset"
            name="ethnicity"
            :error="!!errors.length"
            v-model="data.ethnicity"
            :options="ethnicities" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="gender" ref="gender"
        :rules="{required: true}" :events="['input']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Gender"
          :error-message="errors[0]">
          <input-radio-list
            name="gender"
            :error="!!errors.length"
            v-model="data.gender"
            :list="genders"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="schoolLevel" ref="schoolLevel"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Current school level"
          :error-message="errors[0]">
          <input-select-basic
            @open="reset"
            name="schoolLevel"
            :error="!!errors.length"
            v-model="data.schoolLevel"
            :options="schoolLevels" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="highschool" ref="highschool"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="High school name"
          :error-message="errors[0]">
          <input-select-dinamic
            @open="reset"
            name="highschool"
            :error="!!errors.length"
            v-model="data.highschool"
            placeholder="High school name" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="highschoolGraduationDate" ref="highschoolGraduationDate"
        :rules="{required: true}" :events="['none']">
        <input-item slot-scope="{ errors, reset, validate }"
          class="my-account-form__item"
          title="High school graduation date"
          :error-message="errors[0]">
          <input-date
            type="tel"
            name="highschoolGraduationDate"
            @dirty="validate"
            @open="reset"
            :error="!!errors.length"
            v-model="data.highschoolGraduationDate"
            :show-day="false"
            :yearStart="now.getFullYear() - 69"
            :yearStop="now.getFullYear() + 9" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="enrolled" ref="enrolled"
        :rules="{required: true}" :events="['input']">
        <input-item slot-scope="{ errors, reset }"
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

      <ValidationProvider name="enrollmentDate" ref="enrollmentDate"
        :rules="{required: true}" :events="['none']">
        <input-item slot-scope="{ errors, reset, validate }"
          class="my-account-form__item"
          title="College enrollment date"
          :error-message="errors[0]">
          <input-date
            type="tel"
            @open="reset"
            @dirty="validate"
            name="enrollmentDate"
            :error="!!errors.length"
            v-model="data.enrollmentDate"
            :show-day="false"
            :yearStart="now.getFullYear() - 6"
            :yearStop="now.getFullYear() + 14" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider
        ref="universities"
        name="universities"
        :rules="data.enrolled === null || data.enrolled.value ? 'required' : 'required|array_min:3'"
        :events="['close']">
        <input-item slot-scope="{ errors, reset, validate }"
          class="my-account-form__item"
          :title="data.enrolled === null || data.enrolled.value ? 'College name' : 'Potential college names'"
          :error-message="errors[0]">
          <input-select-dinamic
            v-if="data.enrolled === null || data.enrolled.value"
            @open="reset"
            placeholder="College name"
            name="university"
            @input="setUniversity"
            :value="university"
            v-model="university"
            :error="!!errors.length"/>
          <input-select-group
            v-else
            @open="reset"
            placeholder="College name"
            name="university"
            :error="!!errors.length"
            v-model="data.universities"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="collegeGraduationDate" ref="collegeGraduationDate"
        :rules="{required: true}" :events="['none']">
        <input-item slot-scope="{ errors, reset, validate }"
          class="my-account-form__item"
          title="College graduation date"
          :error-message="errors[0]">
          <input-date
            type="tel"
            name="collegeGraduationDate"
            @open="reset"
            @dirty="validate"
            :error="!!errors.length"
            v-model="data.collegeGraduationDate"
            :show-day="false"
            :yearStart="now.getFullYear() - 69"
            :yearStop="now.getFullYear() + 31" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="degree" ref="degree"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Field of study (Major)"
          :error-message="errors[0]">
          <input-select-basic
            name="degree"
            @open="reset"
            :error="!!errors.length"
            v-model="data.degree"
            :options="degrees" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="degreeType" ref="degreeType"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Degree type"
          :error-message="errors[0]">
          <input-select-basic
            name="degreeType"
            @open="reset"
            :error="!!errors.length"
            v-model="data.degreeType"
            :options="degreeTypes" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="gpa" ref="gpa"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="GPA"
          :error-message="errors[0]">
          <input-select-basic
            @open="reset"
            name="gpa"
            :error="!!errors.length"
            v-model="data.gpa"
            :options="gpas" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="careerGoal" ref="careerGoal"
        :rules="{required: true}" :events="['close']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Career goal"
          :error-message="errors[0]">
          <input-select-basic
            name="careerGoal"
            @open="reset"
            :error="!!errors.length"
            :options="careerGoals"
            v-model="data.careerGoal" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="studyOnline" ref="studyOnline"
      :rules="{required: true}">
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

      <input-item
        class="my-account-form__item"
        title="Military affiliation"
        :error-message="errors[0]">
        <input-select-basic
          :maxHeight="100"
          name="militaryAffiliation"
          v-model="data.militaryAffiliation"
          :options="militaryAffiliations" />
      </input-item>
    </section>
    <Button class="reg2-form__btn" ref="button"
      @click.native="send" theme="orange" size="xl"
      :label="contentSet.textButton" :show-loader="isSubmitting" />

      <p class="text5 reg2-form__disclaimer">
        * ScholarshipOwl keeps your personal details confidential and only collects information required to find scholarships and process applications.
      </p>
  </div>
</template>

<script>
import Vue                    from "vue";
import moment                 from "moment-timezone";
import { mapGetters }         from "vuex";
import { ValidationProvider, Validator } from "vee-validate";
import { scroll }             from "lib/utils/dom.js";
import {toLabelValueObj, mmddyyyyToDate, stringToLabelValueObj}
    from "components/Pages/MyAccount/SubTabs/initialInputDataFormaters";
import mixpanel                       from "lib/mixpanel";
import { REGISTER_2_BUTTON_CLICK }    from "lib/mixpanel";
import { validator }          from "components/Pages/MyAccount/SubTabs/validator";
import { firePixel }          from "lib/utils/tracking";
import InputRadioList         from "components/Common/Input/Radio/InputRadioList.vue";
import InputDate              from "components/Common/Input/Select/InputDate.vue";
import AccordionItem          from "components/Common/Accordion/AccordionItemNew.vue";
import InputSelectBasic       from "components/Common/Input/Select/InputSelectBase.vue";
import InputSelectDinamic     from "components/Common/Input/Select/InputSelectDinamic.vue";
import InputSelectGroup       from "components/Common/Input/Select/InputSelectGroup.vue";
import InputItem              from "components/Common/Input/InputItem.vue"
import Button                 from "components/Common/Buttons/ButtonCustom.vue";

const v = new Validator();

const fieldsPriority = [
  "profileType",
  "dateOfBirth",
  "citizenship",
  "ethnicity",
  "gender",
  "schoolLevel",
  "highschool",
  "highschoolGraduationDate",
  "enrolled",
  "enrollmentDate",
  "universities",
  "collegeGraduationDate",
  "degree",
  "degreeType",
  "gpa",
  "careerGoal",
  "studyOnline",
  "militaryAffiliation"
]

const getMessage = (val, fieldName, enrolled) => {
  if(val && val.label) {
    return [val.label];
  }

  if("universities".indexOf(fieldName) > -1) {
    if(!enrolled || enrolled.value) return val.slice(0, 1);
    if(enrolled && enrolled.value) return val;
  }

  if(Array.isArray(val)) return val;

  if("dateOfBirth".indexOf(fieldName) > -1) {
    return [moment(val).format('MM/DD/YYYY')];
  }

  if(["highschoolGraduationDate", "collegeGraduationDate",
      "enrollmentDate"].indexOf(fieldName) > -1) {
    return [moment(val).format('MM/YYYY')];
  }

  throw Error('Something wrong');
}

const next = (itemsLength, currentIndex, callback) => {
  let index = currentIndex;

  index += 1;

  while(index < itemsLength) {
    if(callback(index)) {
      currentIndex = 0;
      break;
    }

    index += 1;
  }

  if(!currentIndex) return;

  index = 0;
  itemsLength = currentIndex;

  while(index < itemsLength) {
    if(callback(index)) {
      break;
    }

    index += 1;
  }
}

function applyInitValues(fieldNames) {
  if(!fieldNames || !Array.isArray(fieldNames) || !fieldNames.length) return;

    fieldNames.map(name => {
      let data = this.data[name];

      if(!data) return;

      v.verify(data, this.getValidationRules(name), {name})
        .then(({valid, errors}) => {
          this.$refs[name].isValid = valid;
          this.$refs[name].messages = !valid
            ? [errors[0]] : getMessage(data, name, this.data.enrolled);
        })
    });
}

function setBeforeReloadData() {
  const beforeUnloadData = this.unloadStore.getData("register2");

  if(!beforeUnloadData) return false;

  const beforeUnloadDataNames = Object.keys(beforeUnloadData);

  beforeUnloadDataNames.forEach(name => {
    let value = beforeUnloadData[name];

    if(name.toLowerCase().indexOf('date') > -1 && value) {
      value = new Date(value);
    }

    Vue.set(this.data, name, value);
  });

  if(this.isMobile) {
    applyInitValues.call(this, beforeUnloadDataNames.filter(name => beforeUnloadData[name]));
  }

  return true;
}

function setInitialData() {
  if(!setBeforeReloadData.call(this)) {
    this.$store.dispatch('account/fetchData', ['profile'])
      .then(this.setDataFromProfile)
  }
}

export default {
  name: "Register2Form",
  mixins: [validator],
  components: {
    ValidationProvider,
    InputRadioList,
    AccordionItem,
    InputDate,
    InputSelectBasic,
    InputSelectDinamic,
    InputSelectGroup,
    InputItem,
    Button
  },
  props: {
    contentSet: {type: Object, default: {}},
    isSubmitting: {type: Boolean, default: false},
    serverFieldErrors: {type: Object, default: null},
  },
  created() {
    this.$store.dispatch(
      "options/load", {name: [
        "profileTypes",
        "genders",
        "citizenships",
        "ethnicities",
        "gpas",
        "degrees",
        "degreeTypes",
        "careerGoals",
        "schoolLevels",
        "studyOnline",
        "militaryAffiliations",
      ], callback: () => {
        this.$emit('loaded');
      }}
    );

    setInitialData.call(this);
  },
  mounted() {
    if(this.isMobile) {
      this.$refs[fieldsPriority[this.currentItemIndex]].isOpen = true;
    }

    firePixel({...this.marketing, goalName: "LEAD"});
  },
  data() {
    return {
      data: {
        gender: null,
        dateOfBirth: null,
        schoolLevel: null,
        degree: null,
        universities: null,
        enrolled: null,
        citizenship: null,
        militaryAffiliation: {label: "None", value: "0"}
      },
      currentItemIndex: 0,
      isTryiedToSubmit: false
    }
  },
  watch: {
    data: {
      handler() {
        this.saveToLocaStorage();
      },
      deep: true
    },
    isMobile(val) {
      if(val) setInitialData.call(this);
    },
    'data.enrolled'(val) {
      if(this.isMobile || !this.$refs["universities"]) return;

      if(val.value && this.data.universities
        && !this.data.universities.length) {
        this.data.universities = null;
      }

      if(!this.isTryiedToSubmit) {
        this.$refs["universities"].reset();
        return;
      }

      this.$nextTick(() => {
        this.$refs["universities"].reset();
        this.$refs["universities"].validate();
      })
    }
  },
  computed: {
    now() {
      return new Date();
    },
    isMobile() {
      return this.xs || this.s || this.m;
    },
    university() {
      let result = this.data.universities && this.data.universities[0]
        ? {label: this.data.universities[0], value: this.data.universities[0]}
        : null;

      return result;
    },
    ...mapGetters({
      xs:                   "screen/xs",
      s:                    "screen/s",
      m:                    "screen/m",
      l:                    "screen/l",
      xl:                   "screen/xl",
      xxl:                  "screen/xxl",
      isUSA:                "account/isUSA",
      profile:              "account/profile",
      yesOrNo:              "options/yesOrNo",
      label:                "options/label",
      option:               "options/option",
      profileTypes:         "options/profileTypes",
      genders:              "options/genders",
      citizenships:         "options/citizenships",
      ethnicities:          "options/ethnicities",
      gpas:                 "options/gpas",
      degrees:              "options/degrees",
      degreeTypes:          "options/degreeTypes",
      careerGoals:          "options/careerGoals",
      schoolLevels:         "options/schoolLevels",
      studyOnline:          "options/studyOnline",
      militaryAffiliations: "options/militaryAffiliations",
      srcLoaded:            "options/srcLoaded",
      marketing:            "account/marketing",
    }),
  },
  methods: {
    setDataFromProfile() {
      if(this.profile.degree) {
        this.data.degree      = toLabelValueObj(this.profile.degree);
      }

      if(this.profile.schoolLevel) {
        this.data.schoolLevel = toLabelValueObj(this.profile.schoolLevel);
      }

      if(this.profile.dateOfBirth) {
        this.data.dateOfBirth = mmddyyyyToDate(this.profile.dateOfBirth);
      }

      if(this.profile.gender) {
        this.data.gender      = stringToLabelValueObj(this.profile.gender);
      }

      if(!this.isUSA && this.profile.citizenship) {
        let { id: value, name: label } = this.profile.citizenship;

        this.data.citizenship = { label, value };
      }

      if(this.isMobile) {
        applyInitValues.call(this, ["degree", "schoolLevel", "dateOfBirth", "gender", "citizenship"]);
      }
    },
    setUniversity(val) {
      if(!this.data.universities) {
        this.isMobile
          ? this.input([val.label], 'universities')
          : this.data.universities = [val.label]

        return;
      }

      this.data.universities.splice(0, 1, val.label)

      if(this.isMobile) {
        this.input(this.data.universities, 'universities')
      }
    },
    saveToLocaStorage() {
      this.unloadStore.saveData("register2", this.data);
    },
    dateValidate(val, fieldName) {
      if(!val) return;

      this.input(val, fieldName);
    },
    handleEnrolledChanges(val) {
      this.input(val, 'enrolled');

      if(!this.data.universities || !this.data.universities.length) return;

      const UNIVERSITIES = "universities";

      let rules = this.getValidationRules(UNIVERSITIES);

      v.verify(this.data.universities, rules, {name: UNIVERSITIES})
        .then(({ valid, errors }) => {
          this.$refs[UNIVERSITIES].isValid = valid;

          if(!valid) {
            this.$refs[UNIVERSITIES].messages = [errors[0]];
            return;
          }

          let message = getMessage(this.data.universities, UNIVERSITIES, this.data.enrolled);

          if(val.value) {
            message = message.slice(0, 1);
          }

          this.$refs[UNIVERSITIES].messages = message;
        })
    },
    universitiesValidate(val, fieldName) {
      Vue.set(this.data, "universities", val);

      this.$refs["universities"].isValid = true;
      this.$refs["universities"].messages = getMessage(val, fieldName);

      if(val.length < 3) return;

      this.input(val, fieldName);
    },
    getValidationRules(fieldName) {
      if(fieldName === "dateOfBirth") {
        return {
          required: true,
          age: 16
        }
      }

      if(fieldName === "universities" && this.data.enrolled) {
        return this.data.enrolled.value
          ? {required: true}
          : {required: true, array_min: 3}
      }

      if(fieldName === "militaryAffiliation") {
        return null;
      }

      return "required";
    },
    input(val, fieldName) {
      Vue.set(this.data, fieldName, val);

      this.$nextTick(() => {
        let rules = this.getValidationRules(fieldName);

        const validate = ({ valid, errors }) => {
          this.$refs[fieldName].isValid = valid;

          if(!valid) {
            this.$refs[fieldName].messages = [errors[0]];
            return;
          }

          this.$refs[fieldName].messages = getMessage(val, fieldName, this.data.enrolled);
          this.next()
        }

        if(rules === null) {
          validate({ valid: true })
          return;
        }

        v.verify(val, rules, {name: fieldName})
          .then(validate)
          .catch(err => new Error(err))
        })
    },
    next() {
      this.$refs[fieldsPriority[this.currentItemIndex]].isOpen = false;

      let item = null;

      next(fieldsPriority.length, this.currentItemIndex, index => {
        if(!this.$refs[fieldsPriority[index]].isValid) {
          this.currentItemIndex = index;

          this.$refs[fieldsPriority[index]].isOpen = true;

          item = this.$refs[fieldsPriority[index]].$el;

          return true;
        }

        item = this.$refs["button"].$el;
      })

      scroll(item);
    },
    open(refName) {
      let nextItemId = fieldsPriority.indexOf(refName);
      let prevItemName = fieldsPriority[this.currentItemIndex];

      const goToNext = () => {
        this.$refs[prevItemName].isOpen = false;
        this.currentItemIndex = nextItemId;
        this.$refs[fieldsPriority[nextItemId]].isOpen = true;
      }

      if(this.currentItemIndex === nextItemId) {
        this.$refs[prevItemName].isOpen = true;
        return;
      }

      let rules = this.getValidationRules(prevItemName);

      if(rules === null) {
        goToNext()
        return;
      }

      v.verify(this.data[prevItemName], rules, {name: prevItemName})
        .then(({valid, errors}) => {
          this.$refs[prevItemName].isValid = valid;

          let message = valid
            ? getMessage(this.data[prevItemName], prevItemName, this.data.enrolled)
            : [errors[0]];

          this.$refs[prevItemName].messages = message;
          goToNext();
        })
        .catch(console.log);
    },
    validateAllAccordion() {
      let promisses = [];

      fieldsPriority.forEach(name => {
        let rules = this.getValidationRules(name);

        if(this.$refs[name] && rules !== null) {
          promisses.push(v.verify(this.data[name], rules, {name})
            .then(({ valid, errors }) => {
              this.$refs[name].isValid = valid;

              if(!valid) {
                this.$refs[name].messages = [errors[0]];
              }

              this.$refs[name].isOpen = false;

              return valid;
            }))
        }
      })

      return new Promise((resolve, reject) => {
        Promise.all(promisses).then(values => {
          if(!values.every(valid => valid)) {
            this.currentItemIndex = fieldsPriority.length - 1;
            this.next();
            resolve(false);
          }

          resolve(true);
        })
      })
    },
    prepareData() {
      let data = {
        profileType:          this.data.profileType.value,
        schoolLevel:          this.data.schoolLevel.value,
        gender:               this.data.gender.value,
        dateOfBirth:          moment(this.data.dateOfBirth).format('MM/DD/YYYY'),
        ethnicity:            this.data.ethnicity.value,
        citizenship:          this.data.citizenship.value,
        highschool:           this.data.highschool.label,
        enrolled:             this.data.enrolled.value,
        enrollmentYear:       this.data.enrollmentDate ?
          this.data.enrollmentDate.getFullYear() : null,
        enrollmentMonth:      this.data.enrollmentDate ?
          this.data.enrollmentDate.getMonth() + 1: null,
        universities:         this.data.enrolled.value
          ? this.data.universities.slice(0, 1) : this.data.universities,
        gpa:                  this.data.gpa.value,
        highschoolGraduationMonth: this.data.highschoolGraduationDate
          ? this.data.highschoolGraduationDate.getMonth() + 1 : null,
        highschoolGraduationYear: this.data.highschoolGraduationDate
          ? this.data.highschoolGraduationDate.getFullYear() : null,
        graduationYear:       this.data.collegeGraduationDate ?
          this.data.collegeGraduationDate.getFullYear() : null,
        graduationMonth:      this.data.collegeGraduationDate ?
          this.data.collegeGraduationDate.getMonth() + 1: null,
        degree:               this.data.degree.value,
        degreeType:           this.data.degreeType.value,
        careerGoal:           this.data.careerGoal.value,
        studyOnline:          this.data.studyOnline.label.toLowerCase(),
      };

      if(this.data.militaryAffiliation) {
        data["militaryAffiliation"] = this.data.militaryAffiliation.value;
      }

      this.$emit("submit", data);
    },
    send() {
      this.isTryiedToSubmit = true;

      mixpanel.track(REGISTER_2_BUTTON_CLICK);

      if(this.isMobile) {
        this.validateAllAccordion()
          .then(valid => valid && this.prepareData())
      } else {
        this.validateAll(this.$refs).then(valid => {
          if(!valid) {
            this.scrollToError(this.$refs, fieldsPriority);
            return;
          }

          this.prepareData();
        })
      }
    }
  }
};
</script>

<style lang="scss">
  // global variables
  // assets/sass/style-gide/palette/_variables.scss
  // $geyser

  .reg2-form {
    padding-bottom: 140px;

    &__btn {
      margin-top: 25px;

      @include breakpoint($s) {
        margin-top: 30px;
      }

      @include breakpoint($m) {
        max-width: 424px;
        margin-left: auto;
        margin-right: auto;
      }
    }

    &__disclaimer {
      margin-top: 23px;
      text-align: center;
      line-height: 1.5em;
    }

    .input-item__title {
      margin-left: 5px;
    }

    .input-item__error {
      font-size: 12px;
    }
  }

  .reg2-input-set {
    display: grid;
    grid-template-columns: 1fr;
    grid-column-gap: 28px;
    grid-row-gap: 14px;
    box-sizing: border-box;
    width: 100%;
    max-width: 904px;
    margin-right: auto;
    margin-left: auto;

    @include breakpoint($m) {
      grid-row-gap: 20px;
      grid-template-columns: 1fr 1fr;
    }
  }

  .accordion-register {
    border: 2px solid #dde3e7;
  }
</style>