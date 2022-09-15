<template>
  <div class="field-conditions">
    <b-field
      horizontal
      class="condition-type"
      :label="label"
      :message="errors.first('conditionType')"
      :type="errors.has('conditionType') ? 'is-danger' : null">
      <b-select
        v-model="field.eligibilityType"
        name="conditionType"
        v-validate.disable="'required'"
        data-vv-as="condition type"
      >
        <option v-for="(name, type) in eligibilityTypes" :value="type" :key="type" >
          {{ name }}
        </option>
      </b-select>
    </b-field>
    <div class="between-control" v-if="field.eligibilityType === 'between'">
      <b-field
        :message="errors.first('conditionValue')"
        :type="errors.has('conditionValue') ? 'is-danger' : null">
        <b-input
          type="text"
          name="conditionValue"
          :value="betweenFirstValue"
          @input="setBetweenFirstValue"
          v-validate.disable="valueValidation"
          data-vv-as="condition value"
        />
      </b-field>
      <span>&nbsp;-&nbsp;</span>
      <b-field
        :message="errors.first('conditionValue2')"
        :type="errors.has('conditionValue2') ? 'is-danger' : null">
        <b-input
          type="text"
          name="conditionValue2"
          :value="betweenSecondValue"
          @input="setBetweenSecondValue"
          v-validate.disable="valueValidation"
          data-vv-as="condition value"
        />
      </b-field>
    </div>
    <b-field
      v-else
      class="condition-value"
      :message="errors.first('conditionValue')"
      :type="errors.has('conditionValue') ? 'is-danger' : null">
      <b-select
        v-if="eligibilityOptions"
        name="conditionValue"
        :multiple="isMultiple"
        :value="selectedValueOption"
        @input="selectValueOption"
        v-validate.disable="valueValidation"
        data-vv-as="condition type"
      >
        <option v-for="(option, type) in eligibilityOptions" :value="type" :key="type" >
          {{ option && option.name ? option.name : option }}
        </option>
      </b-select>
      <b-input
        v-else
        type="text"
        name="conditionValue"
        v-model="field.eligibilityValue"
        v-validate.disable="valueValidation"
        data-vv-as="condition value"
      />
    </b-field>
    <b-field>
      <button class="button is-success is-round" @click="save">
        <b-icon icon="check" />
      </button>
      <button class="button is-grey is-round" @click="close">
        <b-icon icon="close" />
      </button>
    </b-field>
  </div>
</template>
<script>
import { ELIGIBILITY_TYPE_IN, ELIGIBILITY_TYPE_NOT_IN, ELIGIBILITY_TYPE_BETWEEN } from 'store/fields';
import { createItemStore } from 'lib/store/factory';

export default {
  name: 'FieldCondition',
  props: {
    scholarshipField: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      field: this.scholarshipField.copy(),
    };
  },
  methods: {
    selectValueOption(val) {
      Vue.set(this.field, 'eligibilityValue', this.isMultiple ? val.join(',') : val);
    },
    setBetweenFirstValue(val) {
      Vue.set(this.field, 'eligibilityValue', [val, this.betweenSecondValue].join(','));
    },
    setBetweenSecondValue(val) {
      Vue.set(this.field, 'eligibilityValue', [this.betweenFirstValue, val].join(','));
    },
    async save() {
      if (await this.$validator.validateAll()) {
        this.$emit('input', this.field);
      }
    },
    close() {
      this.$emit('close', this.scholarshipField);
    },
  },
  computed: {

    label({ field: { field: { id, name } } }) {
      switch (id) {
        case 'dateOfBirth':
          return 'Applicant\'s age';
          break;
        default:
          return name;
      }
    },

    label: ({ eligibilityRule, field: { field } }) => eligibilityRule.label || field.name,

    valueValidation: ({ eligibilityRule: { validation } }) => 'required' + (validation ? `|${validation}` : ''),

    eligibilityRule: ({ $store, field: { field } }) => $store.getters['fields/eligibilityRule'](field.id),

    eligibilityOptions: ({ $store, field: { field } }) => {
      const options = $store.getters['fields/find'](field.id)['options'];
      return options && Object.keys(options).length ? options : null;
    },

    isMultiple: ({ field: { eligibilityType } }) =>
      [ELIGIBILITY_TYPE_IN, ELIGIBILITY_TYPE_NOT_IN].indexOf(eligibilityType) > -1,

    eligibilityTypes: ({ eligibilityRule }) => eligibilityRule['types'],

    selectedValueOption: ({ isMultiple, field: { eligibilityValue } }) =>
      isMultiple ? ( eligibilityValue ? eligibilityValue.split(',') : [] ) : eligibilityValue,

    betweenFirstValue: ({ field: { eligibilityValue } }) => eligibilityValue ? eligibilityValue.split(',')[0] : null,

    betweenSecondValue: ({ field: { eligibilityValue } }) => eligibilityValue ?  eligibilityValue.split(',')[1] : null,

  },
  watch: {
    scholarshipField(field) {
      this.$data.field = field.copy();
    },
    'field.eligibilityType': function(n, o) {
      if (n === ELIGIBILITY_TYPE_BETWEEN || o === ELIGIBILITY_TYPE_BETWEEN) {
        this.field.eligibilityValue = null;
      }
    },
  }
}
</script>
<style lang="scss" scoped>
.field-conditions {
  .between-control {
    display: flex;
  }
  display: flex;
  /deep/ .field {
    &.condition-type {
      .label {
        min-width: 120px;
      }
      margin-right: 10px;
    }
    &.condition-value {
      margin-right: 10px;
    }
  }
  .button {
    &.is-round {
      border-radius: 50%;
      padding: 0;
      width: 31px;
      height: 31px;
      &.is-success {
        background: #83D476;
      }
      &.is-grey {
        background: #8C8C8C;
      }
      &:not(:first-child) {
        margin-left: 7px;
      }
    }
  }
}
</style>
