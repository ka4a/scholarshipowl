import Vuex from 'vuex';
import { jsona, JsonaModel } from 'lib/jsona.js';
import { prepareQueryString } from './utils';


const baseURL = '/api/';

const requestDefaultOptions = {
  baseURL,
  basicQuery: {},
  include: [],
  fields: {},
};

export default function ItemStore(resourceKey, options) {
  if (typeof options === 'object' && options.id && typeof options.getType === 'function') {
    options = { item: options };
  }

  const defaultOptions = {
    item: new JsonaModel(resourceKey),
    loadUrl: null,
    updateMethod: 'PATCH',
  };

  const config = Object.assign(defaultOptions, requestDefaultOptions, options);
  const backupItem = JsonaModel.clone(config.item);

  const prepareURL = ({ config }, id) => {
    const qs = prepareQueryString(config.basicQuery, config);

    if (typeof config.loadUrl === 'function') {
      return config.loadUrl(id) + (qs ? `?${qs}` : '');
    }

    if (typeof config.loadUrl === 'string') {
      return config.loadUrl + (qs ? `?${qs}` : '');
    }

    return config.baseURL + resourceKey + (id ? `/${id}` : '') + (qs ? `?${qs}` : '');
  };

  return {
    namespaced: true,
    state: {
      config,
      item: config.item,
      loading: false,
      data: null,
    },
    mutations: {
      setLoading(state, loading) {
        Vue.set(state, 'loading', !!loading);
      },
      setItem(state, item) {
        if (item && state.item) {
          if (state.item.readOnlyAttributes) {
            item.setReadOnlyAttributes(state.item.readOnlyAttributes());
          }
          if (state.item.readOnlyRelationships) {
            item.setReadOnlyRelationships(state.item.readOnlyRelationships());
          }
        }
        Vue.set(state, 'item', item);
      },
      setRelated(state, { related, item }) {
        Vue.set(state.item, related, item);
      },
      setLoading(state, loading) {
        Vue.set(state, 'loading', loading);
      },
      setData(state, data) {
        Vue.set(state, 'data', data);
      },
      deteleItem(state) {
        Vue.set(state, 'item', null);
      },
      updateConfig(state, config) {
        Vue.set(state, 'config', Object.assign({}, state.config, config));
      },
    },
    actions: {
      load({ state, commit, dispatch }, id) {
        commit('setLoading', true);
        dispatch('setLoading', true, { root: true });
        dispatch('setLoading', true);
        return axios.get(prepareURL(state, id))
          .then(response => {
            const item = jsona.deserialize(response.data);

            commit('setLoading', false);
            dispatch('setLoading', false, { root: true });
            dispatch('setLoading', false);

            if (!item) {
              return Promise.reject('Got empty data');
            }

            commit('setItem', item);
            commit('setData', response.data);
            return item;
          })
          .catch(error => {
            commit('setLoading', false);
            dispatch('setLoading', false, { root: true });
            dispatch('setLoading', false);
            throw error;
          })
      },
      reload({ state, dispatch }) {
        if (!state.item.id) {
          throw new Error('Can\'t reload store without setted ID');
        }

        dispatch('load', state.item.id);
      },
      save ({ state, commit, dispatch }, options = {}) {
        let contentType = 'application/vnd.api+json';
        const item = options.item ? options.item : state.item
        dispatch('setLoading', true);
        dispatch('setLoading', true, { root: true });
        return axios
          .request({
            url: prepareURL(state, item.id),
            method: state.item.id ? config.updateMethod : 'post',
            data: (options && options.form) ? options.form : item.serialize(options),
            headers: {
              'Content-Type': contentType,
            },
          })
          .then(response => {
            const item = jsona.deserialize(response.data);
            dispatch('setLoading', false);
            dispatch('setLoading', false, { root: true });
            commit('setItem', item);
            commit('setData', response.data);
            return item;
          })
          .catch(error => {
            dispatch('setLoading', false);
            dispatch('setLoading', false, { root: true });
            throw error.response;
          })
      },
      delete({ state, dispatch }, model = {}) {
        dispatch('setLoading', true, { root: true });
        return axios.delete(prepareURL(state, model.id || state.item.id))
          .then(response => {
            dispatch('setLoading', false, { root: true });
            dispatch('reset');
            return response;
          })
          .catch(error => {
            dispatch('setLoading', false, { root: true });
            throw error;
          })
      },
      saveRelated({ state, dispatch }, options) {
        const { related, item } = options;
        const { links } = state.item;

        if (!links[related] || !links[related].related) {
          throw new Error('Can\'t find related link!');
        }

        const qs = prepareQueryString(state.config.basicQuery, state.config);
        const url = links[related].related + (qs ? `?${qs}` : '');

        commit('setLoading', true);
        dispatch('setLoading', true, { root: true });

        return axios
          .request({
            url,
            method: item.id ? options.updateMethod : 'post',
            data: (options && options.form) ? options.form : item.serialize(options.serialize || {}),
            headers: {
              'Content-Type': 'application/vnd.api+json',
            }
          })
          .then(response => {
            const item = jsona.deserialize(response.data);
            dispatch('setLoading', false, { root: true });
            commit('setLoading', false);
            commit('setRelated', related, item);
            return item;
          })
          .catch(error => {
            commit('setLoading', false);
            dispatch('setLoading', false, { root: true });
            throw error.response;
          })
      },
      resetLoaded({ state, commit }) {
        commit('setItem', jsona.deserialize(state.data))
      },
      reset({ commit }) {
        commit('setItem', JsonaModel.clone(backupItem))
      },
      set({ commit }, data) {
        commit('setItem', new JsonaModel(resourceKey, data));
      },
      setItem({ commit }, model) {
        commit('setItem', model);
      },
      setLoading({ commit }, loading) {
        commit('setLoading', !!loading);
      },
      updateConfig({ commit }, config) {
        commit('updateConfig', config);
      },
      ...((options && options.extend && options.extend.actions) ? options.extend.actions : {})
    },
    getters: {
      item: ({ item }) => item,
      loading: ({ loading }) => loading,
    }
  };
}


export const createStore = (resourceKey, data, options = {}) => {
  let attributes = null;
  let relationships = null;

  if (data && data.attributes) {
    attributes = data.attributes;
  }

  if (data && data.relationships) {
    relationships = data.relationships;
  }

  options.item = JsonaModel.new(resourceKey, attributes, relationships);

  if (Array.isArray(options.readOnlyAttributes)) {
    options.item.setReadOnlyAttributes(options.readOnlyAttributes);
  }

  if (Array.isArray(options.readOnlyRelationships)) {
    options.item.setReadOnlyRelationships(options.readOnlyRelationships);
  }

  return new Vuex.Store(ItemStore(resourceKey, options));
}


export const createModelStore = (model, options) => {
  if (!model || typeof model !== 'object' || !model instanceof JsonaModel) {
    throw new Error('`model` should be instance of `JsonaModel`');
  }

  return new Vuex.Store(
    ItemStore(
      model.getType(),
      Object.assign({}, options, { item: model }),
    )
  )
}
