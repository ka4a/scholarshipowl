import queryString from 'qs';

export const prepareQueryString = (basicQuery, options) => {
  const q = Object.assign({}, basicQuery);

  const include = options.include;
  if (Array.isArray(include) && include.length > 0) {
    q['include'] = include.join(',');
  }

  const fields = options.fields;
  if (typeof fields === 'object' && Object.keys(fields).length > 0) {
    q['fields'] = fields;
  }

  return queryString.stringify(q, { arrayFormat: 'index' });
};
