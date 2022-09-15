import axios from 'axios';
import { GridStore } from 'lib/store/factory.js';

import integrations from './integrations.js';

const search = GridStore('scholarship', { baseURL: '/api/' });

export default {
  namespaced: true,
  modules: {
    integrations,
    search,
  },
  state: {},
  mutations: {},
  actions: {}
}
