<template>
  <section class="recurring-tab">
    <h3 class="recurring-tab__title ma-title">Recurring scholarship settings</h3>
    <p class="recurring-tab__text paragraph16-18">Recurring or renewable scholarship applications are submitted automatically.</p>
    <input-radio-list
      class="recurring-tab__radio"
      name="recurring_application"
      :error="!!errors.length"
      v-model="value"
      appear="vertical"
      :list="list">
      <span class="recurring-tab__tooltip-holder" @click.stop v-tooltip.top-start="{ content: `Automatic application is a premium feature, please Upgrade to activate`, classes: ['ma-tooltip ma-tooltip-recurrence'], trigger: 'click hover', offset: 8 }"></span>
    </input-radio-list>
    <p class="ma-disclaimer recurring-tab__disclaimer">* When there is a change in scholarship automatic application will be stopped.</p>
    <Button class="my-account-form__submit recurring-tab__btn" @click.native="submit"
      theme="orange" size="l" label="SAVE CHANGES" />
  </section>
</template>

<script>
  import { mapGetters } from "vuex";
  import mixpanel from "lib/mixpanel";
  import RadioList from "components/Common/Form/RadioList.vue";
  import Vue from "vue";
  import { VTooltip } from "v-tooltip";
  import { UpdateUserProfile } from "resource";
  import Title from "components/Common/Typography/Title.vue";
  import InputRadioList from "components/Common/Input/Radio/InputRadioList.vue";
  import Button from "components/Common/Buttons/ButtonCustom.vue";

  Vue.directive("v-tooltip", VTooltip);

  const RECURRENT_APPLY_DISABLED = 0;
  const RECURRENT_APPLY_ASAP = 1;
  const RECURRENT_APPLY_ON_DEADLINE = 2;
  const RECURRENT_APPLY_NOTIFY = 3;

  let list = [{
    label: "Automatically apply 24 hours before deadline",
    value: 2,
  }, {
    label: "Don’t enter me automatically",
    value: 0,
  }]

  const itemByValue = value => {
    if(value === undefined || value === null)
      return null;

    let item = null;

    list.forEach(recItem => {
      if(recItem.value === value) {
        item = recItem;
      }
    })

    return item;
  }

  let sentValue;

  export default {
    components: {
      Title,
      InputRadioList,
      Button,
    },
    props: {
      recurringApplication: {type: Number, required: true}
    },
    data() {
      return {
        value: itemByValue(this.recurringApplication),
        submitting: false,
      }
    },
    watch: {
      value(val) {
        if(val.value === RECURRENT_APPLY_ON_DEADLINE) {
          mixpanel.track('Automatically_Apply_Recurrence_Click');
        }
      }
    },
    computed: {
      ...mapGetters({
        profile: "account/profile",
        isMember: "account/isMember"
      }),
      list() {
        if(!this.isMember) {
          list[0]["disabled"] = true;
          list[0]["tooltip"] = true;
        }

        return list;
      },
    },
    methods: {
      submit() {
        if(!this.value) return;

        let value = this.value.value;

        if(value === this.recurringApplication || sentValue === value
          || this.submitting) return;

        let data = {
          recurring_application : value
        }

        this.submitting = true;

        sentValue = value;

        UpdateUserProfile.recurrence(data)
          .then(response => {
            if(response.status !== 200) return;

            return this.$store.dispatch('account/fetchAndUpdateField', ['profile']);
          })
          .then(() => {
            this.submitting = false;
            this.$emit('updated');
          })
          .catch(response => {
            this.submitting = false;
          })
      }
    }
  }
</script>

<style lang="scss">
  $radio-list-fm: "radio-list-fm";
  $radio-item-disable-color: #cdd7e8;
  $light: #ffffff;

  .account-tab {
    .recurring-tab {
      width: 100%;
      max-width: 904px;

      &__text {
        margin-top: 23px;
      }

      &__radio {
        margin-top: 23px;
      }

      &__tooltip-holder {
        position: absolute;
        left: 0; right: 0;
        top: 0; bottom: 0;
        cursor: help;
      }

      &__disclaimer {
        margin-top: 18px;

        @include breakpoint($s) {
          margin-top: 20px;
        }
      }

      &__btn {
        margin-top: 25px;

        @include breakpoint($m) {
          margin-top: 30px;
        }
      }
    }
  }

  .ma-tooltip-recurrence {
    max-width: 360px;
    letter-spacing: -0.2px;

    @include breakpoint($s) {
      letter-spacing: 0;
    }

    &:before {
      content: '';
      position: absolute;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 0 22px 12px 0;
      border-color: transparent #354c6d transparent transparent;
      left: 13px; bottom: -10px;
    }
  }
</style>