import jsona, { JsonaModel } from 'lib/jsona';
import { ItemStore, GridStore } from 'lib/store/factory.js';
import store from 'store';
import axios from 'axios';

export const emptyIframe = (templateId) => {
  const template = JsonaModel.instance(templateId, 'scholarship_template');
  return JsonaModel.new(
    'iframe', {
      width: '100%',
      height: null,
      source: 'iframe',
    }, {
      template
    }
  );
};

export default {
  ...ItemStore('scholarship_template', {
    include: ['iframes'],
  })
};
