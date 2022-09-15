import deepmerge from 'deepmerge';
import GridStore from 'lib/store/grid-store';

export const FIELD_STATE = 'state';
export const FIELD_DATE_OF_BIRTH = 'dateOfBirth';
export const FIELD_SCHOOL_LEVEL = 'schoolLevel';
export const FIELD_FIELD_OF_STUDY = 'fieldOfStudy';

export const ELIGIBILITY_TYPE_EQUALS   = 'eq';
export const ELIGIBILITY_TYPE_NOT      = 'neq';
export const ELIGIBILITY_TYPE_LT       = 'lt';
export const ELIGIBILITY_TYPE_LTE      = 'lte';
export const ELIGIBILITY_TYPE_GT       = 'gt';
export const ELIGIBILITY_TYPE_GTE      = 'gte';
export const ELIGIBILITY_TYPE_IN       = 'in';
export const ELIGIBILITY_TYPE_NOT_IN   = 'nin';
export const ELIGIBILITY_TYPE_BETWEEN  = 'between';

export const defaultEligibilityNames = {
  ELIGIBILITY_TYPE_EQUALS: 'Equal',
  ELIGIBILITY_TYPE_NOT: 'Not equal',
  ELIGIBILITY_TYPE_LT: 'Less than',
  ELIGIBILITY_TYPE_LTE: 'Less or equal',
  ELIGIBILITY_TYPE_GT: 'Greater than',
  ELIGIBILITY_TYPE_GTE: 'Greater or equal',
  ELIGIBILITY_TYPE_IN: 'In list',
  ELIGIBILITY_TYPE_NOT_IN: 'Not in list',
  ELIGIBILITY_TYPE_BETWEEN: 'Between',
};

const fieldEligibilityRules = {
  [FIELD_DATE_OF_BIRTH]: {
    label: 'Applicant\'s age',
    validation: 'numeric',
    types: {
      [ELIGIBILITY_TYPE_EQUALS]: 'is',
      [ELIGIBILITY_TYPE_LT]: 'less than',
      [ELIGIBILITY_TYPE_LTE]: 'less or is',
      [ELIGIBILITY_TYPE_GT]: 'greater than',
      [ELIGIBILITY_TYPE_GTE]: 'greater or is',
      [ELIGIBILITY_TYPE_BETWEEN]: 'is between',
    }
  },
  [FIELD_STATE]: {
    types: {
      [ELIGIBILITY_TYPE_EQUALS]: 'is',
      [ELIGIBILITY_TYPE_NOT]: 'not',
      [ELIGIBILITY_TYPE_IN]: 'one of',
      [ELIGIBILITY_TYPE_NOT_IN]: 'not one of',
    }
  },
  [FIELD_SCHOOL_LEVEL]: {
    types: {
      [ELIGIBILITY_TYPE_EQUALS]: 'is',
      [ELIGIBILITY_TYPE_NOT]: 'not',
      [ELIGIBILITY_TYPE_IN]: 'one of',
      [ELIGIBILITY_TYPE_NOT_IN]: 'not one of',
    }
  },
  [FIELD_FIELD_OF_STUDY]: {
    types: {
      [ELIGIBILITY_TYPE_EQUALS]: 'is',
      [ELIGIBILITY_TYPE_NOT]: 'not',
      [ELIGIBILITY_TYPE_IN]: 'one of',
      [ELIGIBILITY_TYPE_NOT_IN]: 'not one of',
    }
  },
};

export default deepmerge(
  GridStore('field'), {
  getters: {
    hasEligibilityRule: () => (field) => !!fieldEligibilityRules[field],
    eligibilityRule: () => (field) => {
      const rule = fieldEligibilityRules[field];

      if (!rule) {
        throw new Error(`Eligibility rule not found for ${field}`);
      }

      return rule;
    },
  },
});
