import Vue from 'vue';
import { jsona, JsonaModel } from 'lib/jsona';

const defaultInclude = 'include=application,scholarship.website,scholarship.content';

export default {
  namespaced: true,
  state: {
    item: null,
    filled: false,
    disqualified: false,
  },
  mutations: {
    setItem(state, item) {
      Vue.set(state, 'item', item);
    },
    setFilled(state, filled) {
      Vue.set(state, 'filled', !!filled);
    },
    setDisqulified(state, disqualified) {
      Vue.set(state, 'disqualified', !!disqualified);
    },
    setApplication(state, id) {
      Vue.set(state, 'application', id);
    }
  },
  actions: {
    markFilled({ commit }, filled) {
      commit('setFilled', filled);
    },
    load({ commit }, id) {
      return axios.get('/api/application/' + id + '/winner?'+ defaultInclude)
        .then((response) => {
          const item = jsona.deserialize(response.data);
          commit('setItem', item);
          commit('setFilled', !!response.data.data.meta.filled);
          commit('setDisqulified', !!response.data.data.meta.disqualified);
          return item;
        })
    },
    save({ state, commit }, formData) {
      if (!state.item) {
        throw new Error('Please load winner before save!');
      }
      return axios.post('/api/application/' + state.item.application.id + '/winner?' + defaultInclude, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
        .then((response) => {
          const item = jsona.deserialize(response.data);
          commit('setItem', item);
          commit('setFilled', !!response.data.data.meta.filled);
          commit('setDisqulified', !!response.data.data.meta.disqualified);
          return item;
        })
    }
  },
  getters: {
    loaded: ({ item }) => !!item
  }
}
