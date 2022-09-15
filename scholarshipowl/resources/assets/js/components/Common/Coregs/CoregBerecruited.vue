<template lang="html">
  <section class="berecruited-coreg">
    <div class="berecruited-coreg__top">
      <img class="berecruited-coreg__logo" :src="img.logo" alt="Berecruited">
      <p class="text5">NCSA is the largest recruiting network used by over 35,000 college coaches. To begin your recruiting process they will email you a FREE recruiting profile.
      <a style="font-weight: 600" onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=500');return false;"
         href="http://www.ncsasports.org/terms-and-conditions-of-use" target="_blank">Go here for terms and conditions.</a>
      </p>
    </div>

    <p style="text-align: center; margin-top: 23px" class="text5">Do you want to play a sport in college?</p>

    <input-radio-list
      class="berecruited-coreg__radio-list"
      name="berecruited"
      :list="form.wantToPlayList"
      :value="wantToPlay"
      @input="wantToPlayStateHolder" />

    <p class="berecruited-coreg__quote">
      <span class="text6">"Keep sending prospects our way. You guys are great!"</span>
      <span class="text5">- NCAA D1 Head Coach</span>
    </p>

    <div class="berecruited-form" v-if="wantToPlay.value === '1'">
      <p class="berecruited-form__title">Great! We just need a little bit of information.</p>

      <check-box class="berecruited-form__top-ch" name="isParent" v-model="data.athlete_or_parent" @input="emitCoregData">
        <span slot="label">I am a parent filling out on behalf of my child</span>
      </check-box>

        <!-- TODO if parent seet it from register 2 step -->

 <!-- <interaction-item label="Parent First Name" :forInput="form.firstName.name" :error="errors.first(form.firstName.name)">
          <interaction-input
            :type="form.firstName.type"
            :id="form.firstName.name"
            :name="form.firstName.name"
            placeholder="Parent First Name"
            :value="data.parent_first_name"
            @input="v => { data.parent_first_name = v; emitCoregData(); }"
            v-validate="'required'"
            :valid="!errors.has(form.firstName.name)"
          />
        </interaction-item> -->
      <div class="berecruited-form__grid">
        <ValidationProvider name="parentFirstName" ref="parentFirstName"
          :rules="{required: true}" :events="['blur']">
          <input-item slot-scope="{ errors, reset }"
            class="my-account-form__item"
            title="Parent first name"
            :error-message="errors[0]">
            <input-text
              @focus="reset"
              :format="nameCapFistLetter"
              :error="!!errors.length"
              name="parentFirstName"
              placeholder="Parent first name"
              autocomplete="name given-name"
              @input="emitCoregData"
              v-model="data.parent_first_name"/>
          </input-item>
        </ValidationProvider>

        <ValidationProvider name="parentLastName" ref="parentLastName"
          :rules="{required: true}" :events="['blur']">
          <input-item slot-scope="{ errors, reset }"
            class="my-account-form__item"
            title="Parent last name"
            :error-message="errors[0]">
            <input-text
              @focus="reset"
              :format="nameCapTwoFistLetters"
              :error="!!errors.length"
              name="parentLastName"
              placeholder="Parent last name"
              autocomplete="name given-name"
              @input="emitCoregData"
              v-model="data.parent_last_name"/>
          </input-item>
        </ValidationProvider>

        <ValidationProvider name="email" ref="parentEmail"
          :rules="{required: true, email: true}" :events="['blur', 'validate']">
          <input-item slot-scope="{ errors, reset }"
            class="my-account-form__item"
            title="Parent email"
            :error-message="errors[0]">
            <input-text
              @focus="reset"
              @input="reset();emitCoregData()"
              :error="!!errors.length"
              type="email"
              name="email"
              placeholder="Parent email"
              autocomplete="new-password"
              v-model="data.parent_email"/>
          </input-item>
        </ValidationProvider>

        <ValidationProvider name="phone" ref="parentPhone"
          :rules="{required: true, min: 14}" :events="['blur']">
          <input-item slot-scope="{ errors, reset }"
            class="my-account-form__item"
            title="Parent phone number"
            :error-message="errors[0]">
            <input-text
              @focus="reset"
              :error="!!errors.length"
              :format="formatUSAPhoneNumber"
              type="tel"
              name="phone"
              placeholder="Parent phone number"
              autocomplete="phone"
              @input="emitCoregData"
              v-model="data.parent_phone_number"/>
          </input-item>
        </ValidationProvider>

        <ValidationProvider name="highschoolGraduationYear" ref="highschoolGraduationYear"
          :rules="{required: true}" :events="['close']">
          <input-item slot-scope="{ errors, validate, reset }"
            class="my-account-form__item"
            title="High School graduation year"
            :error-message="errors[0]">
            <input-select-basic
              @open="reset()"
              :error="!!errors.length"
              name="highschoolGraduationYear"
              @input="emitCoregData"
              v-model="data.graduation_year"
              :options="form.graduation" />
          </input-item>
        </ValidationProvider>

        <ValidationProvider name="sport" ref="sport"
          :rules="{required: true}" :events="['close']">
          <input-item slot-scope="{ errors, validate, reset }"
            class="my-account-form__item"
            title="Sport"
            :error-message="errors[0]">
            <input-select-basic
              @open="reset()"
              :error="!!errors.length"
              name="sport"
              @input="emitCoregData"
              v-model="data.sport_id"
              :options="form.sport" />
          </input-item>
        </ValidationProvider>
      </div>
    </div>
  </section>
</template>

<script>
import { name,
  formatUSAPhoneNumber }        from "lib/utils/format";
import { ValidationProvider }   from "vee-validate";
import InputRadioList           from "components/Common/Input/Radio/InputRadioList.vue";
import InputText                from "components/Common/Input/Text/InputTextBasic.vue";
import InputSelectBasic         from "components/Common/Input/Select/InputSelectBase.vue";
import CheckBox                 from "components/Common/CheckBoxes/CheckBoxBasic.vue";
import InputItem                from "components/Common/Input/InputItem.vue"

const nameCapFistLetter = name(1),
      nameCapTwoFistLetters = name(2);

const wantToPlayYes = { label: "yes", value: "1" },
      wantToPlayNo = { label: "no", value: "0" };

export default {
  name: "coreg-berecruited",
  props: {
    id: {type: Number, required: true},
    extra: {type: Object, default: {}},
    checked: {type: Boolean, default: false}
  },
  components: {
    ValidationProvider,
    InputRadioList,
    InputText,
    InputSelectBasic,
    CheckBox,
    InputItem
  },
  data: function() {
    return {
      wantToPlay: null,
      form: {
        wantToPlayList: [
          wantToPlayYes,
          wantToPlayNo
        ],
        graduation: (() => {
          let currentYear = new Date().getFullYear(),
            graduateYear = currentYear + 6,
            years = [];

          while(currentYear <= graduateYear) {
            years.push({label: currentYear, value: currentYear});
            currentYear++;
          }

          return years;
        })(),
        sport: [
          { "label": "Baseball", "value": "17706" },
          { "label": "Field Hockey", "value": "17711" },
          { "label": "Football", "value": "17633" },
          { "label": "Men's Basketball", "value": "17638" },
          { "label": "Men's Diving", "value": "17652" },
          { "label": "Men's Golf", "value": "17659" },
          { "label": "Men's Ice Hockey", "value": "17665" },
          { "label": "Men's Lacrosse", "value": "17707" },
          { "label": "Men's Rowing", "value": "17644" },
          { "label": "Men's Soccer", "value": "17683" },
          { "label": "Men's Swimming", "value": "17687" },
          { "label": "Men's Tennis", "value": "17689" },
          { "label": "Men's Volleyball", "value": "17695" },
          { "label": "Men's Water Polo", "value": "17701" },
          { "label": "Softball", "value": "17634" },
          { "label": "Women's Basketball", "value": "17639" },
          { "label": "Women’s Beach Volleyball", "value": "17730" },
          { "label": "Women's Diving", "value": "17653" },
          { "label": "Women's Golf", "value": "17660" },
          { "label": "Women's Ice Hockey", "value": "17666" },
          { "label": "Women's Lacrosse", "value": "17708" },
          { "label": "Women's Rowing", "value": "17645" },
          { "label": "Women's Soccer", "value": "17684" },
          { "label": "Women's Swimming", "value": "17688" },
          { "label": "Women's Tennis", "value": "17690" },
          { "label": "Women's Track", "value": "17692" },
          { "label": "Women's Volleyball", "value": "17696" },
          { "label": "Women's Water Polo", "value": "17702" },
          { "label": "Women's Wrestling", "value": "17635" }
        ]
      },
      data: {
        athlete_or_parent: null,
        parent_first_name: "",
        parent_last_name: "",
        parent_email: "",
        parent_phone_number: "",
        graduation_year: null,
        sport_id: null,
      },
      img: {
        logo: "../assets/img/coreg/berecruited_logo.png",
      }
    };
  },
  watch: {
    extra() {
      this.changePropsFormat();
    }
  },
  created() {
    this.wantToPlay = this.checked
      ? wantToPlayYes : wantToPlayNo;

    this.changePropsFormat();

    this.emitCoregData();

    this.emitFieldsForValidation();
  },
  methods: {
    wantToPlayStateHolder(state) {
      this.wantToPlay = state;

      this.emitFieldsForValidation();

      this.emitCoregData();
    },
    emitFieldsForValidation() {
      if(!this.wantToPlay) return;

      this.$emit("berecruited", !!Number(this.wantToPlay.value)
        ? this.$refs : null);
    },
    changePropsFormat() {
      if(!this.extra) return;

      for(let key in this.extra) {
        this.data[key] = this.extra[key];
      }

      if(this.extra.sport_id) {
        this.data.sport_id = this.form.sport.find(item => item.value === this.extra.sport_id);
      }

      if(this.extra.graduation_year) {
        this.data.graduation_year = this.form.graduation.find(item => item.value === this.extra.graduation_year);
      }

      if(this.extra.athlete_or_parent) {
        this.data.athlete_or_parent = this.extra.athlete_or_parent === 'parent';
      }
    },
    emitCoregData() {
      let berecruitedData = {
        athlete_or_parent:   this.data.athlete_or_parent
          ? "parent" : "student",
        parent_first_name:   this.data.parent_first_name,
        parent_last_name:    this.data.parent_last_name,
        parent_email:        this.data.parent_email,
        parent_phone_number: this.data.parent_phone_number,
        graduation_year:     this.data.graduation_year
          ? this.data.graduation_year.value : "",
        sport_id:            this.data.sport_id
          ? this.data.sport_id.value : ""
      };

      this.$emit("coreg", "berecruited", {
        id      : this.id,
        checked : Number(this.wantToPlay.value),
        extra   : berecruitedData
      });
    },
    nameCapFistLetter,
    nameCapTwoFistLetters,
    formatUSAPhoneNumber
  }
};
</script>

<style lang="scss">
  $blue-light: #919daf;
  $grey-light: #c4c4c4;

  .text5 {
    font-size: 12px;
    line-height: 1.93em;
    color: $blue-light;

    a {
      color: #495d7b;
    }
  }

  .text6 {
    font-size: 12px;
    color: $grey-light;
    font-style: italic;
  }

  .berecruited-coreg {
    &__top {
      flex-direction: column;
      display: flex;
      align-items: center;

      @include breakpoint($m) {
        flex-direction: row;
      }
    }

    &__quote {
      text-align: center;
      margin-top: 14px;
    }

    &__logo {
      max-width: 129px;

      @include breakpoint(max-width $m - 1px) {
        margin-bottom: 14px;
      }

      @include breakpoint($m) {
        margin-right: 14px;
      }
    }

    &__radio-list {
      display: flex;
      justify-content: center;
      margin-top: 7.5px;

      .mdc-form-field__label {
        text-transform: capitalize;
      }
    }

    .radio-list-fm {
      padding-top: 10px;
      padding-left: 0;
      width: 116px;
      margin: auto;
    }
  }

  .berecruited-form {
    margin-top: 23px;

    &__title {
      font-size: 16px;
      font-weight: bold;
      letter-spacing: 0.04px;
      text-align: center;
      line-height: 1.4em;
      color: $blue-light;
    }

    &__top-ch {
      margin-top: 23px;
      display: flex;
      justify-content: center;
    }

    &__grid {
      margin-top: 25px;

      @include breakpoint($s) {
        margin-top: 30px;
      }

      display: grid;
      grid-template-columns: 1fr;
      grid-column-gap: 28px;
      grid-row-gap: 14px;
      box-sizing: border-box;
      width: 100%;
      margin-right: auto;
      margin-left: auto;

      @include breakpoint($m) {
        grid-row-gap: 20px;
        grid-template-columns: 1fr 1fr;
      }
    }
  }
</style>