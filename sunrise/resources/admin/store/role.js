import axios from 'axios';
import jsona from 'lib/jsona.js';
import { GridStore, DictStore } from 'lib/store/factory.js'

const resourceKey = 'role';

export default {
  namespaced: true,
  modules: {
    grid: GridStore(resourceKey, { baseURL: '/api/' }),
    dict: DictStore(resourceKey),
  },
  actions: {
    load({ dispatch }) {
      return dispatch('grid/load');
    },
    delete({ dispatch }, role) {
      return axios.delete('/api/' + resourceKey + '/' + role.id)
        .then(response => {
          dispatch('grid/load');
          dispatch('dict/reload');
          return response;
        })
    },
    save ({ dispatch }, role) {
      return axios
        .request({
            method: role.id ? 'put' : 'post',
            url: '/api/' + resourceKey + (role.id ? `/${role.id}` : ''),
            data: jsona.serialize({ stuff: role }),
          })
        .then(response => {
          dispatch('grid/load');
          dispatch('dict/reload');
          return response;
        })
    },
  },
  getters: {
    options({ grid }) {
      return grid.collection ? grid.collection
        .map(role => ({ label: role.name, value: role.id })) : [];
    }
  }
}
