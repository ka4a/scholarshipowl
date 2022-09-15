import 'es6-promise/auto';
import Vue from 'vue';
import Vuex from 'vuex';
import mutations from '../store/mutations';
Vue.use(Vuex);

import fields from './fields.js';
import requirements from './requirements.js';

import user from './user.js';
import role from './role.js';
import permissions from './permissions.js';
import organisation from './organisation.js';
import templates from './templates.js';
import winners from './winners.js';
import modals from './modals.js';
import settings from './settings';

import winnerInformation from './winnerInformation.js';

import scholarships from './scholarships';

//=======vuex store start===========
const store = new Vuex.Store({
  modules: {
    user,
    role,
    templates,
    scholarships,
    organisation,
    permissions,
    winners,
    modals,
    winnerInformation,
    fields,
    requirements,
    settings,
  },
  state: {
    /* Open terms modal on login layout */
    termsModal: false,

    errors: [],
    left_open: true,
    preloader: true,
    loading: false,
    cal_events: [{
      id: 0,
      title: 'Office',
      start: '2017-04-30',
      end: '2017-04-30'
    }, {
      id: 1,
      title: 'Holidays',
      start: '2017-04-01',
      end: '2017-04-01'
    }]
  },
  mutations: {
    setLoading(state, loading) {
      Vue.set(state, 'loading', loading);
    },
    setErrors(state, errors) {
      Vue.set(state, 'errors', errors);
    }
  },
  actions: {
    setLoading({ commit }, loading) {
      commit('setLoading', loading);
    },
    setErrors({ commit }, errors) {
      commit('setErrors', errors);
    }
  }
});
//=======vuex store end===========
export default store
