import { ItemStore, GridStore } from 'lib/store/factory.js';
import axios from 'axios';

import scholarshipSettings from './organisation/scholarshipSettings.js';

export default {
  namespaced: true,
  modules: {

    list: GridStore('organisation', {
      include: ['owners'],
    }),

    scholarshipSettings,

    /**
     * Store for scholarship template view.
     */
    scholarshipTemplate: ItemStore('scholarship_template', {
      include: ['website', 'published']
    }),

    scholarshipsPublishedPage: ItemStore('scholarship', {
      include: ['template', 'website', 'fields', 'requirements', 'stats']
    }),

    /**
     * List of organisation "scholarship_template".
     */
    scholarships: GridStore('scholarships', {
      baseURL: ({ rootGetters }) => `/api/organisation/${rootGetters['user/workingOrganisation']}/`,
      include: ['website', 'published'],
      sorting: {
        field: 'id',
        direction: 'desc',
      },
    }),

    /**
     * Store for winners list page.
     */
    winners: GridStore('winners', {
      baseURL: ({ rootGetters }) => `/api/organisation/${rootGetters['user/organisation'].id}/`,
      include: ['application', 'scholarship', 'scholarship_winner'],
      basicQuery: {
        filter: {
          disqualifiedAt: { operator: 'eq', value: null }
        }
      }
    }),

  },
  state: {

    /**
     * Identify if organisation have scholarships to use on scholarships
     * list page.
     *
     * @param boolean
     */
    hasScholarships: null,

  },
  mutations: {
    setHasScholarships(state, hasScholarships) {
      state.hasScholarships = !!hasScholarships;
    }
  },
  actions: {
    setHasScholarships({ commit }, hasScholarships) {
      commit('setHasScholarships', hasScholarships);
    }
  }
}
