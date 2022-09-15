const studyCountrieKeys = [
  'studyCountry1',
  'studyCountry2',
  'studyCountry3',
  'studyCountry4',
  'studyCountry5'
];

export const toLabelValueObj = (() => {
  let keys = {
    name: 'label',
    text: 'label',
    id: 'value'
  }

  return value => {
    if(!value || value === null) return null;

    let valueKeys = Object.keys(value);

    let result = {
      [keys[valueKeys[0]]]: `${value[valueKeys[0]]}`,
      [keys[valueKeys[1]]]: `${value[valueKeys[1]]}`
    }

    return result;
  }
})();

export const booleanToYesNoList = value => {
  if(value === null || typeof value !== 'boolean') return null;

  return {
    label: value ? "Yes" : "No",
    value: Number(value)
  }
}

export const yymmddToDate = (year, month, day = 1) => {
  if(!year || !month) return null;

  return new Date(year, month - 1, day);
}

export const strArrayToLableValueObj = value => {
  if(!Array.isArray(value) || !value.length) return null;

  return value.map(u => ({label: u, value: Math.round(Math.random() * 1000)}))
}

export const stringToLabelValueObj = value => {
  if(!value || typeof value !== 'string') return null;

  return {label: value, value }
}

export const mmddyyyyToDate = value => {
  if(value === null) return null;

  return new Date(value);
}

export const valuesFromObject = (keys, target) => {
  if(!keys || !Array.isArray(keys) || !target || typeof target !== 'object')
    throw Error('Please provide correct arguments');

  return keys.map(key => target[key]).filter(item => item)
}

const arrayToLableValueObj = value => {
  if(!value || !Array.isArray(value)) return;

  return value.map(item => toLabelValueObj(item))
}

export const studyCountries = source => {
  if(!source) throw Error('Please provide soruce');

  let result = arrayToLableValueObj(
    valuesFromObject(
      studyCountrieKeys,
      source
    )
  );

  return result.length ? result : null;
}