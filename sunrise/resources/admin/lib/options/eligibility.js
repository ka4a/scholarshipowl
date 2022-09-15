import Options from 'lib/options.js';

import citizenshipJson from 'lib/dictionary/data/citizenship.json';
import countryJson from 'lib/dictionary/data/country.json';
import degreeJson from 'lib/dictionary/data/degree.json';
import degreeTypeJson from 'lib/dictionary/data/degree_type.json';
import ethnicityJson from 'lib/dictionary/data/ethnicity.json';
import fieldJson from 'lib/dictionary/data/field.json';
import militaryAffiliationJson from 'lib/dictionary/data/military_affiliation.json';
import scholarshipStatusJson from 'lib/dictionary/data/scholarship_status.json';
import schoolLevelJson from 'lib/dictionary/data/school_level.json';
import stateJson from 'lib/dictionary/data/state.json';

export const citizenship = new Options(citizenshipJson);

export const country = new Options(countryJson);

export const degree = new Options(degreeJson);

export const degreeType = new Options(degreeTypeJson);

export const ethnicity = new Options(ethnicityJson);

export const field = new Options(fieldJson);

export const FIELD_GENDER = 10;
export const FIELD_CITIZENSHIP = 11;
export const FIELD_ETHNICITY = 12;
export const FIELD_COUNTRY = 14;
export const FIELD_STATE = 15;
export const FIELD_SCHOOL_LEVEL = 19;
export const FIELD_DEGREE = 20;
export const FIELD_DEGREE_TYPE = 21;
export const FIELD_GPA = 24;
export const FIELD_MILITARY_AFFILIATION = 64;
export const FIELD_COUNTRY_OF_STUDY = 65;

export const FIELD_TYPE_REQUIRED     = 'required';
export const FIELD_TYPE_VALUE        = 'value';
export const FIELD_TYPE_LESS_THAN    = 'less_than';
export const FIELD_TYPE_GREATER_THAN = 'greater_than';
export const FIELD_TYPE_NOT          = 'not';
export const FIELD_TYPE_IN           = 'in';

export const fieldTypes = new Options([
  {
    'type': FIELD_TYPE_REQUIRED,
    'label': 'Required',
  },
  {
    'type': FIELD_TYPE_VALUE,
    'label': 'Equals',
  },
  {
    'type': FIELD_TYPE_LESS_THAN,
    'label': 'Less than',
  },
  {
    'type': FIELD_TYPE_GREATER_THAN,
    'label': 'Greater then',
  },
  {
    'type': FIELD_TYPE_NOT,
    'label': 'Not equals',
  },
  {
    'type': FIELD_TYPE_IN,
    'label': 'In list',
  },
], 'type', 'label')

export const militaryAffiliation = new Options(militaryAffiliationJson);

export const scholarshipStatus = new Options(scholarshipStatusJson);

export const schoolLevel = new Options(schoolLevelJson);

export const state = new Options(stateJson);
