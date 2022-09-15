import Vuex from 'vuex';
import debounce from 'lodash/debounce';
import throttle from 'lodash/throttle';
import { jsona, JsonaModel } from 'lib/jsona.js';

import { prepareQueryString } from './utils';
export { default as GridStore, createStore as createGridStore } from './grid-store';
export { default as ItemStore } from './item-store';
export { createStore as createItemStore } from './item-store';
export { createModelStore as createModelStore } from './item-store';

const baseURL = '/api/';

export const DictStore = (resourceKey) => {
  const allResource = debounce(() => axios.get(baseURL + resourceKey), 200, {
    leading: true
  });

  return {
    namespaced: true,
    state: {
      loaded: false,
      collection: [],
    },
    mutations: {
      setLoaded(state, loaded) {
        state.loaded = loaded;
      },
      setCollection(state, items) {
        // let collection = {};
        // items.forEach(item => collection[item.id] = item);
        // Vue.set(state, 'collection', collection);
        Vue.set(state, 'collection', items);
      },
    },
    actions: {
      reload({ commit, dispatch }) {
        commit('setLoaded', false);
        return dispatch('load');
      },
      load({ state, commit }) {
        if (!state.loaded) {
          return allResource(resourceKey)
            .then(response => {
              commit('setCollection', jsona.deserialize(response.data));
            })
        }
      }
    },
    getters: {
      find({ collection }) {
        return id => {
          for (let i=0; i < collection.length; i++) {
            if (collection[i].id === id) {
              return collection[i];
            }
          }
        }
      },
      list({ collection }) {
        return collection;
      }
    }
  }
}
