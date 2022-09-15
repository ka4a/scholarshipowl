<template>
  <span v-if="scholarshipField.eligibilityType && scholarshipField.eligibilityValue">
    <span>{{ label }}</span>
    <span>{{ eligibilityName }}</span>
    <span>{{ eligibilityValueFormated }}</span>
    <!-- <span>
      <b-tag v-if="eligibilityOptions" v-for="tag in scholarshipField.eligibilityValue.split(',')" :key="tag">
        {{ optionName(tag) }}
      </b-tag>
      <b-tag v-else>{{ tag }}</b-tag>
    </span> -->
  </span>
</template>
<script>
import { ELIGIBILITY_TYPE_IN, ELIGIBILITY_TYPE_NOT_IN, ELIGIBILITY_TYPE_BETWEEN } from 'store/fields';

export default {
  name: 'FieldConditionView',
  props: {
    scholarshipField: {
      type: Object,
      required: true,
    }
  },
  computed: {

    label: ({ eligibilityRule, scholarshipField: { field } }) => eligibilityRule.label || field.name,


    eligibilityRule: ({ $store, scholarshipField }) =>
      $store.getters['fields/eligibilityRule'](scholarshipField.field.id),

    eligibilityName: ({ eligibilityRule, scholarshipField: { eligibilityType } }) =>
      eligibilityRule['types'][eligibilityType],

    isMultiple: ({ scholarshipField: { eligibilityType } }) =>
      [ELIGIBILITY_TYPE_IN, ELIGIBILITY_TYPE_NOT_IN].indexOf(eligibilityType) > -1,


    eligibilityOptions: ({ $store, scholarshipField: { field } }) => {
      const options = $store.getters['fields/find'](field.id)['options'];
      return options && Object.keys(options).length ? options : null;
    },

    eligibilityValueFormated: ({ optionName, eligibilityOptions, scholarshipField }) => {
      const { eligibilityValue, eligibilityType } = scholarshipField;

      if (eligibilityOptions) {
        return eligibilityValue.split(',').map(o => optionName(o)).join(', ');
      }

      if (eligibilityType === ELIGIBILITY_TYPE_BETWEEN) {
        return eligibilityValue.split(',', 2).join(' and ');
      }

      return eligibilityValue;
    },

    optionName: ({ eligibilityOptions }) => (id) => {
      const option = eligibilityOptions[id];
      return option.name ? option.name : option;
    },
  }
}
</script>
