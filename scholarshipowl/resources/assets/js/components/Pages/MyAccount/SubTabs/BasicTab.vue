<template>
  <div class="my-account-form">
    <ValidationProvider name="firstName" ref="firstName"
      :rules="{required: !!profile.firstName}" :events="['blur']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="First name"
        :error-message="errors[0]">
        <input-text
          @focus="reset"
          :format="nameCapFistLetter"
          :error="!!errors.length"
          name="firstName"
          placeholder="First name"
          autocomplete="name given-name"
          v-model="data.firstName"/>
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="lastName" ref="lastName"
      :rules="{required: !!profile.lastName}" :events="['blur']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="Last name"
        :error-message="errors[0]">
        <input-text
          @focus="reset"
          :format="nameCapTwoFistLetters"
          :error="!!errors.length"
          name="lastName"
          placeholder="Last name"
          autocomplete="name given-name"
          v-model="data.lastName"/>
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="dateOfBirth" ref="dateOfBirth"
      :rules="{required: !!profile.dateOfBirth, age: 16}" :events="['close']">
      <input-item slot-scope="{ errors, reset }"
        class="my-account-form__item"
        title="Birthdate"
        :error-message="errors[0]">
        <input-date
          @open="applyAfter(100, reset)"
          type="tel"
          name="dateOfBirth"
          :error="!!errors.length"
          v-model="data.dateOfBirth"
          :yearStop="new Date().getFullYear() - 16" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="gender" ref="gender"
      :rules="{required: !!profile.gender}">
      <input-item slot-scope="{ errors }"
        class="my-account-form__item"
        title="Gender"
        :error-message="errors[0]">
        <input-radio-list
          appear="horizontal"
          name="gender"
          :error="!!errors.length"
          v-model="data.gender"
          :list="genders"/>
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="profileType" ref="profileType"
      :rules="{required: !!profile.profileType}">
      <input-item slot-scope="{ errors }"
        class="my-account-form__item"
        title="I am a"
        :error-message="errors[0]">
        <input-radio-list
          name="profileType"
          :error="!!errors.length"
          v-model="data.profileType"
          :list="profileTypes"/>
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="citizenship" ref="citizenship"
      :rules="{required: !!profile.citizenship}" :events="['close']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item"
        title="Citizenship"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="citizenship"
          v-model="data.citizenship"
          :options="citizenships" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="ethnicity" ref="ethnicity"
      :rules="{required: !!profile.ethnicity}" :events="['close']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item"
        title="Ethnicity"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="ethnicity"
          v-model="data.ethnicity"
          :options="ethnicities" />
      </input-item>
    </ValidationProvider>

    <ValidationProvider name="militaryAffiliation" ref="militaryAffiliation"
      :rules="{required: !!profile.militaryAffiliation}" :events="['close']">
      <input-item slot-scope="{ errors, validate, reset }"
        class="my-account-form__item"
        title="Military affiliation"
        :error-message="errors[0]">
        <input-select-basic
          @open="reset()"
          :error="!!errors.length"
          name="militaryAffiliation"
          v-model="data.militaryAffiliation"
          :options="militaryAffiliations" />
      </input-item>
    </ValidationProvider>
    <Button class="my-account-form__submit" @click.native="submit"
      theme="orange" size="l" label="SAVE CHANGES" />
  </div>
</template>

<script>
  import { mapGetters }         from "vuex";
  import { mmddyyyy }           from "lib/utils/utils";
  import { name }               from "lib/utils/format";
  import { ValidationProvider } from "vee-validate";
  import InputItem              from "components/Common/Input/InputItem.vue"
  import InputSelectBasic       from "components/Common/Input/Select/InputSelectBase.vue";
  import InputDate              from "components/Common/Input/Select/InputDate.vue";
  import InputRadioList         from "components/Common/Input/Radio/InputRadioList.vue";
  import Button                 from "components/Common/Buttons/ButtonCustom.vue";
  import InputText              from "components/Common/Input/Text/InputTextBasic.vue";
  import {mmddyyyyToDate, stringToLabelValueObj, toLabelValueObj}
    from "components/Pages/MyAccount/SubTabs/initialInputDataFormaters"
  import { validator }        from "components/Pages/MyAccount/SubTabs/validator";

  const fields = [
    'dateOfBirth',
    'firstName',
    'lastName',
    'gender',
    'profileType',
    'citizenship',
    'ethnicity',
    'militaryAffiliation'
  ];

  const nameCapFistLetter = name(1),
        nameCapTwoFistLetters = name(2);

  export default {
    name: "basic-sub-tab",
    mixins: [validator],
    components: {
      ValidationProvider,
      InputItem,
      InputSelectBasic,
      InputDate,
      InputRadioList,
      Button,
      InputText
    },
    props: {
      profile: {type: Object, required: true},
      validationErrors: {type: Object, required: true},
      submiting: {type: Boolean, default: false}
    },
    watch: {
      validationErrors(errors) {
        if(!errors) return;

        this.showErrors(errors);
      }
    },
    beforeCreate() {
      this.$store.dispatch(
        "options/load", [
          "countries",
          "genders",
          "citizenships",
          "ethnicities",
          "militaryAffiliations",
          "profileTypes"
        ]
      );
    },
    created() {
      this.data.firstName =           this.profile.firstName;
      this.data.lastName =            this.profile.lastName;
      this.data.dateOfBirth =         mmddyyyyToDate(this.profile.dateOfBirth);
      this.data.gender =              stringToLabelValueObj(this.profile.gender);
      this.data.profileType =         stringToLabelValueObj(this.profile.profileType);
      this.data.citizenship =         toLabelValueObj(this.profile.citizenship);
      this.data.ethnicity =           toLabelValueObj(this.profile.ethnicity);
      this.data.militaryAffiliation = toLabelValueObj(this.profile.militaryAffiliation);
    },
    computed: {
      ...mapGetters({
        xs:                   "screen/xs",
        s:                    "screen/s",
        countries:            "options/countries",
        genders:              "options/genders",
        citizenships:         "options/citizenships",
        ethnicities:          "options/ethnicities",
        militaryAffiliations: "options/militaryAffiliations",
        profileTypes:         "options/profileTypes"
      }),
      data() {
        return {
          data: {
            firstName: '',
            lastName: '',
            dateOfBirth: null,
            gender: null,
            profileType: null,
            citizenship: null,
            ethnicity: null,
            militaryAffiliation: null
          }
        }
      }
    },
    methods: {
      nameCapFistLetter,
      nameCapTwoFistLetters,
      applyAfter(time, callback) {
        if(!time || !callback) return;

        setTimeout(callback, time);
      },
      select(value, fieldName, caller) {
        this.data[fieldName] = value;
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

            if(this.data[key] !== null && this.data[key].value) {
              data[key] = this.data[key].value;
            }
          })

          if(this.data['dateOfBirth']) {
            data['dateOfBirth'] = mmddyyyy(this.data['dateOfBirth']);
          }

          if(!Object.keys(data).length) return;

          this.$emit('submit', data);
        });
      }
    }
  }
</script>