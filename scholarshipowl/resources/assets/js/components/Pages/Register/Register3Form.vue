<template>
  <div class="base-hor-indent form-wrp reg3-form">
    <div class="reg3-form__inputs">
      <ValidationProvider name="address" ref="address"
        :rules="{required: true}"
        :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Address"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :error="!!errors.length"
            :format="alphaNumeric"
            name="address"
            :placeholder="isUSA ? 'Address' : 'Street address, P.O. box, company name'"
            autocomplete="address-level4"
            v-model="data.address"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider v-if="!isUSA" name="address2" ref="address2"
        :rules="{required: true}"
        :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item reg3-form__address2"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :error="!!errors.length"
            :format="alphaNumeric"
            name="address2"
            placeholder="Apartment, suite, unit, building, floor, etc."
            autocomplete="address-level5"
            v-model="data.address2"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="zip" ref="zip"
        :rules="isUSA ? {required: true, min: 5} : {required: true}"
        :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          :title="isUSA ? 'Zip code' : 'Zip / Postal code'"
          :error-message="errors[0]">
          <input-text v-if="isUSA"
            @focus="reset"
            :error="!!errors.length"
            :format="formatZip"
            :type="isUSA ? 'tel' : 'text'"
            name="zip"
            placeholder="Zip code"
            autocomplete="postal-code"
            @input="autocompleteStateAndCity"
            v-model="data.zip"/>
          <input-text v-else
            @focus="reset"
            :format="alphaNumeric"
            :error="!!errors.length"
            name="zip"
            placeholder="Zip / Postal code"
            autocomplete="postal-code"
            v-model="data.zip"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="city" ref="city"
        :rules="{required: true}"
        :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="City"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :format="capitalize"
            :error="!!errors.length"
            name="city"
            placeholder="City"
            autocomplete="postal-code"
            v-model="data.city"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider v-if="isUSA" name="state"
        :rules="{required: true}"
        ref="state" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="State" :error-message="errors[0]">
          <input-select-basic
            :loading="loading.state"
            @open="reset()"
            :error="!!errors.length"
            name="state"
            :options="states"
            v-model="data.state" />
        </input-item>
      </ValidationProvider>

      <ValidationProvider v-if="!isUSA" name="stateName" ref="stateName"
        rules="required" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="State / Province / Region"
          :error-message="errors[0]">
          <input-text
            type="text"
            @focus="reset"
            :error="!!errors.length"
            :format="capitalize"
            name="stateName"
            placeholder="State / Province / Region"
            autocomplete="new-password"
            v-model="data.stateName"/>
        </input-item>
      </ValidationProvider>

      <input-item v-if="!isUSA"
        class="my-account-form__item" title="Country">
        <input-text
          :value="data.country && data.country.name"
          disabled="disabled"/>
      </input-item>

      <ValidationProvider vid="password" name="password" ref="password"
        rules="required|min:6" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Password"
          :error-message="errors[0]">
          <input-password
            type="password"
            @focus="reset"
            :error="!!errors.length"
            name="password"
            placeholder="Password"
            autocomplete="new-password"
            v-model="password"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider vid="repassword" name="repassword" ref="repassword"
        rules="required|min:6" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Confirm password"
          :error-message="errors[0]">
          <input-password
            type="password"
            @focus="reset"
            :error="!!errors.length"
            name="repassword"
            placeholder="Confirm password"
            autocomplete="new-password"
            v-model="repassword"/>
        </input-item>
      </ValidationProvider>
    </div>

    <coreg-mount-point
      class="reg3-form__coregs"
      v-if="coregsData.above && coregsData.above.length"
      @coreg="coregs => {
        setCoregs(coregs)
        saveToLocalStorage();
      }"
      @berecruited="val => fields.berecruited = val"
      :savedCoregs="data.coregs"
      :coregs="coregsData.above" />

    <div class="reg3-form__btns">
      <Button class="reg3-form__btn"
        :shouldHoldKeyPress="true" @click="register"
        @click.native="register" theme="orange" size="xl"
        :label="contentSet.textButton" :show-loader="isSubmitting" />
      <BtnBackArrow @click.native="toReg2Step" class="reg3-form__btn-back" />
    </div>

    <p class="text5 reg3-form__disclaimer">* ScholarshipOwl keeps your personal details confidential and only collects information required to find scholarships and process applications.</p>
  </div>
</template>

<script>
  import { capitalize }               from "lodash";
  import { mapState, mapGetters,
    mapActions }                      from "vuex";
  import { ROUTES }                   from "router.js";
  import { ValidationProvider }       from "vee-validate";
  import { alphaNumeric, formatZip }  from "lib/utils/format";
  import { AutocompleteResource }     from "resource.js";
  import mixpanel                       from "lib/mixpanel";
  import { REGISTER_3_BUTTON_CLICK }    from "lib/mixpanel";
  import registerMixin                from "components/Pages/Register/mixins";
  import { validator }                from "components/Pages/MyAccount/SubTabs/validator";
  import InputItem                    from "components/Common/Input/InputItem.vue";
  import InputSelectBasic             from "components/Common/Input/Select/InputSelectBase.vue";
  import Button                       from "components/Common/Buttons/ButtonCustom.vue";
  import BtnBackArrow                 from "components/Common/Buttons/ButtonBackArrow.vue"
  import InputText                    from "components/Common/Input/Text/InputTextBasic.vue";
  import InputPassword                from "components/Common/Input/Text/InputPassword.vue";
  import CoregMountPoint              from "components/Common/Coregs/CoregMountPoint.vue";

  export default {
    mixins: [validator, registerMixin],
    components: {
      ValidationProvider,
      InputItem,
      InputSelectBasic,
      Button,
      BtnBackArrow,
      InputText,
      InputPassword,
      CoregMountPoint
    },
    props: {
      contentSet: {type: Object, default: {}},
      isSubmitting: {type: Boolean, default: false},
    },
    created() {
      const requests = [
        this.getCoregs({path: "register3", id: this.accountId}),
        this.$store.dispatch('account/fetchData', ['profile'])
      ];

      if(this.isUSA) {
        requests.push(
          this.$store.dispatch("options/load", ["states"])
        )
      }

      Promise.all(requests).then(results => {
        if(this.profile && this.profile.country) {
          this.data.country = this.profile.country
        }

        this.$emit("loaded")
      })

      this.unloadStore.walkThroughSavedData(
        "register3",
        (name, value) => {
          Vue.set(this.data, name, value);
        }
      )
    },
    data() {
      return {
        data: {
          city: null,
          coregs: {},
          country: null
        },
        loading: {
          state: false,
          city: false
        },
        fields: {
          berecruited: null
        }
      }
    },
    watch: {
      data: {
        handler() {
          this.saveToLocalStorage();
        },
        deep: true
      },
    },
    computed: {
      ...mapGetters({
        isUSA: "account/isUSA",
        states: "options/states",
        profile: "account/profile",
        accountId: "account/accountId",
      }),
      ...mapState({
        coregsData: state => state.coregs.coregsData
      }),
    },
    methods: {
      alphaNumeric,
      formatZip,
      capitalize,
      // TODO move to coregs module like static method and
      // import here
      setCoregs(coregs) {
        if(!Object.keys(coregs).length) {
          delete this.data.coregs
          return;
        }

        this.data.coregs = coregs;
      },
      saveToLocalStorage() {
        this.unloadStore.saveData("register3", this.data);
      },
      toReg2Step() {
        this.$router.push(ROUTES.REGISTER_2);
      },
      autocompleteStateAndCity() {
        let zip = this.data.zip;

        if(zip.length === 5 && !isNaN(zip)) {
          this.loading.state = true;
          this.loading.city = true;

          AutocompleteResource["stateAndCity"]({zip})
            .then((response) => {
              let data = response.data.data;

              if (response.status === 200 && data) {
                if (data.state) {
                  this.data.state = {
                    label: data.state.name,
                    value: data.state.id
                  };
                }
                if (data.city) {
                  this.data.city = data.city;
                }
              }

              this.loading.state = false;
              this.loading.city = false;
            })
            .catch(() => {
              this.loading.state = false;
              this.loading.city = false;
            });
        }
      },
      validateAndPrepare() {
        let fields = this.$refs;

        if(this.fields.berecruited) {
          fields = {
            ...this.$refs,
            ...this.fields.berecruited
          }
        }

        this.validateAll(fields).then(valid => {
          if(!valid) {
            this.scrollToError(fields);

            return;
          }

          let data = {
            address:                 this.data.address,
            city:                    this.data.city,
            zip:                     this.data.zip,
            password:                this.password,
            password_confirmation:   this.repassword,
            generate_one_time_token: true,
          };

          if(!this.isUSA) data["address2"] = this.data.address2;
          if(this.isUSA)  data["state"] = this.data.state.value;
          if(!this.isUSA) data["stateName"] = this.data.stateName;
          if(this.data && this.data.coregs) data["coregs"] = this.data.coregs;

          this.$emit("submit", data);
        })
      },
      register() {
        mixpanel.track(REGISTER_3_BUTTON_CLICK);

        this.validateAndPrepare();
      },
      ...mapActions({
        getCoregs: "coregs/getCoregs"
      }),
    }
  }
</script>

<style lang="scss">
  .reg3-form {
    &__address2 {
      margin-top: -10px;

      @include breakpoint($m) {
        margin-top: 19px;
      }
    }

    &__inputs {
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

    &__btns {
      position: relative;
      max-width: 1090px;
    }

    &__btn {
      position: relative;
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

    &__btn-back {
      @include breakpoint(max-width $m - 1px) {
        margin-top: 23px;
        margin-left: auto;
        margin-right: auto;
      }

      @include breakpoint($m) {
        position: absolute;
        left: 0; top: 0; bottom: 0;
        margin-top: auto;
        margin-bottom: auto;
      }
    }

    &__disclaimer {
      margin-top: 23px;
      text-align: center;
      line-height: 1.5em;
    }

    &__coregs {
      margin-top: 23px;
      margin-left: auto;
      margin-right: auto;
      max-width: 904px;

      .berecruited-coreg {
        margin-top: 15px;
      }
    }

    .coreg-basic +
    .coreg-basic {
      margin-top: 14px;
    }
  }
</style>