import jsona, { JsonaModel } from 'lib/jsona';
import { ItemStore, GridStore } from 'lib/store/factory.js';
import store from 'store';
import axios from 'axios';

export const emptyScholarshipTemplate = () => {
  return JsonaModel.new(
    'scholarship_template',
    {
      title: null,
      description: null,
      amount: null,
      awards: 1,
      timezone: null,
      start: null,
      deadline: null,
      recurrenceValue: null,
      recurrenceType: null,
    }, {
      organisation: store.getters['user/organisation'],
    }
  );
};

export const emptyScholarshipWebsite = () => {
  return JsonaModel.new(
    'scholarship_website', {
      domain: '',
      layout: null,
      variant: null,

      companyName: null,
      title: null,
      intro: null,
    }, {
      logo: null
    }
  );
};

export const emptyIframe = (templateId) => {
  const template = JsonaModel.instance(templateId, 'scholarship_template');
  return JsonaModel.new(
    'iframe', {
      width: null,
      height: null,
      source: null,
    }, {
      template
    }
  );
}

export default {
  ...ItemStore('scholarship_template', {
    include: ['website', 'published'],
    extend: {
      actions: {
        publish: ({ rootState }) => {
          return axios.post(`/api/scholarship_template/${rootState.organisation.scholarshipSettings.item.id}/publish`)
            .then((rsp) => jsona.deserialize(rsp.data));
        },
        republish: ({ rootState }) => {
          return new Promise((resolve, reject) => {
            const template = rootState.organisation.scholarshipSettings.item;
            if (template && template.published && template.published.id) {
              axios.post(`/api/scholarship/${template.published.id}/republish`)
                .then(() => resolve())
                .catch(() => reject())
            } else {
              reject('Scholarship is not published');
            }
          });
        }
      },
    }
  }),
  namespaced: true,
  modules: {
    /**
     * Store used by scholarship settings fields.
     */
    fields: GridStore('scholarship_template_field', {
      path: ({ rootState }) => `scholarship_template/${rootState.organisation.scholarshipSettings.item.id}/fields`
    }),

    iframes: GridStore('iframe', {
      path: ({ rootState }) => `scholarship_template/${rootState.organisation.scholarshipSettings.item.id}/iframe`,
      extend: {
        actions: {
          create({ rootState, dispatch }, iframe) {
            return new Promise((resolve, reject) => {
              axios.request({
                url: `/api/iframe/`,
                method: 'post',
                data: jsona.serialize({ stuff: iframe }),
                headers: {
                  'Content-Type': 'application/vnd.api+json',
                }
              })
                .then((rsp) => resolve(rsp))
                .catch((e) => reject(e));
            });
          },
          update({ rootState, dispatch }, iframe) {
            return new Promise((resolve, reject) => {
              axios.request({
                url: `/api/iframe/${iframe.id}`,
                method: 'patch',
                data: jsona.serialize({ stuff: iframe }),
                headers: {
                  'Content-Type': 'application/vnd.api+json',
                }
              })
                .then((rsp) => resolve(rsp))
                .catch((e) => reject(e));
            });
          },
        }
      }
    }),

    /**
     * Store for content management
     */
    content: ItemStore('scholarship_template_content'),

    /**
     * Store used by scholarship settings design page.
     */
    website: ItemStore('scholarship_website', {
      updateMethod: 'POST',
      basicQuery: { _method: 'PATCH' }
    })

  },
};
