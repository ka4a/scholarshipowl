import Vuex from 'vuex';
import { jsona, JsonaModel } from 'lib/jsona.js';
import { prepareQueryString } from './utils';

// TODO: Move to some different path
const baseURL = '/api/';

export default function GridStore(resourceKey, opt) {

  let source;

  const options = {
    baseURL,

    /**
     * Can be used for creating custom base path.
     * @param function|string
     */
    path: null,

    sorting: [{
      field: null,
      direction: 'asc'
    }],
    include: [],
    fields: {},
    basicQuery: {},
    ...opt
  };

  const preparePath = (context) => {
    const base = typeof options.baseURL === 'function' ? options.baseURL(context) : options.baseURL;
    const path = typeof options.path === 'function' ? options.path(context) : options.path;

    return base + ( path ? path : resourceKey );
  };

  const prepareURL = (context, download = false) => {
    const { query, sorting } = context.state;
    const q = Object.assign({}, options.basicQuery, query);
    const sortArr = (Array.isArray(sorting) ? sorting : [sorting])
      .filter(s => s.field)
      .map(s => (s.direction === 'desc' ? '-' : '') + s.field);

    if (sortArr.length) {
      q.sort = sortArr.join(',');
    }

    if (download && q.page) {
      delete q.page;
    }

    const qs = prepareQueryString(q, options);

    return preparePath(context) + ( download ? '/export' : '' ) + ( qs ? `?${qs}` : '');
  };

  return {
    namespaced: true,
    state: {
      query: null,
      loaded: false,
      loading: false,
      pagination: {
        count: 100,
        current_page: 1,
        per_page: 10,
        total: 0,
        total_pages: 0
      },
      sorting: options.sorting,
      collection: [],
    },
    mutations: {
      setLoading(state, loading) {
        Vue.set(state, 'loading', !!loading);
      },
      setCollection(state, collection) {
        Vue.set(state, 'collection', collection);
        Vue.set(state, 'loaded', true);
      },
      addItem(state, item) {
        state.collection.push(item);
      },
      updateItem(state, { item, id}) {
        const index = state.collection.indexOf(state.collection.find(item => item && (item.id === id)));
        if (index === -1) {
          throw new Error('Can\'t update not exists item');
        }
        Vue.set(state.collection, index, item);
      },
      removeItem(state, item) {
        const index = state.collection.indexOf(item);
        if (index === -1) {
          throw new Error('Can\'t remove not exists item');
        }
        state.collection.splice(index, 1);
      },
      setPagination(state, pagination) {
        Vue.set(state, 'pagination', pagination);
      },
      setSorting(state, sorting) {
        Vue.set(state, 'sorting', sorting);
      },
      setQuery(state, query) {
        Vue.set(state, 'query', query);
      },
      clearFilters(state) {
        Vue.delete(state.query, 'delete');
      },
    },
    actions: {
      load(context) {
        const { state, commit, dispatch } = context;
        commit('setLoading', true);
        dispatch('setLoading', true, { root: true });

        if (source) {
          source.cancel();
        }

        source = axios.CancelToken.source();

        return axios.get(prepareURL(context), { cancelToken: source.token })
          .then(response => {
            let collection = null;
            source = null;
            commit('setLoading', false);
            dispatch('setLoading', false, { root: true });

            if (response.data) {
              collection = jsona.deserialize(response.data);

              commit('setCollection', collection);

              if (response.data.meta && response.data.meta.pagination) {
                commit('setPagination', response.data.meta.pagination);
              }
            }

            return collection;
          })
          .catch(error => {
            if (!axios.isCancel(error)) {
              commit('setLoading', false);
              dispatch('setLoading', false, { root: true });
              throw error;
            }
          })
      },
      save(context, collection) {
        const { state, commit, dispatch } = context;

        commit('setLoading', true);
        dispatch('setLoading', true, { root: true });

        return axios.request({
          url: prepareURL(context),
          method: 'put',
          data: jsona.serialize({ stuff: collection || state.collection }),
          headers: {
            'Content-Type': 'application/vnd.api+json',
          }
        })
          .then(({ data }) => {
            commit('setLoading', false);
            dispatch('setLoading', false, { root: true });

            const collection = jsona.deserialize(data);

            commit('setCollection', collection);

            if (data.meta && data.meta.pagination) {
              commit('setPagination', response.data.meta.pagination);
            }

            return collection;
          });
      },
      download(context) {
        const { state } = context;
        return axios({
          url: prepareURL(context, true),
          method: 'GET',
          responseType: 'blob'
        }).then((response) => {
          const regexp = /.*filename=(.*)/g;
          const contentDisposition = response.headers['content-disposition'];
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(new Blob([response.data]));
          link.setAttribute('download', regexp.exec(contentDisposition)[1]);
          document.body.appendChild(link);
          link.click();
        });
      },
      search({ state, commit, dispatch }, q) {
        commit('setQuery', Object.assign({}, state.query || {},
          { filter: { search: q } }
        ));

        return dispatch('load');
      },
      filter({ state, commit, dispatch }, filter) {
        const query = state.query || {};

        commit('setQuery', { ...query,
          filter: { ...query.filter, ...filter }
        })

        return dispatch('load');
      },
      clearFilters({ state, dispatch, commit }) {
        let newQuery = state.query;
        delete newQuery['filter'];
        commit('setQuery', newQuery);
        return dispatch('load');
      },
      page({ state, dispatch, commit }, page) {
        const load = typeof page.load !== 'undefined' ? !!page.load : true;

        let number = state.pagination.current_page;
        let size = state.pagination.per_page;

        if (page && page.number) {
          number = page.number;
        }

        if (page && page.size) {
          size = page.size;
        }

        commit('setQuery', Object.assign({}, state.query || {},
          { page: { number, size } }
        ));

        if (load) {
          return dispatch('load');
        }
      },
      sort({ state, dispatch, commit }, { field, direction = 'desc', load = true }) {
        commit('setSorting', { field, direction });

        if (load) {
          return dispatch('load');
        }
      },
      setLoading({ commit }, loading) {
        commit('setLoading', loading);
      },
      updateItem({ commit }, options) {
        commit('updateItem', options)
      },
      addItem({ commit }, item) {
        commit('addItem', item);
      },
      removeItem({ commit }, item) {
        commit('removeItem', item);
      },
      ...((options && options.extend && options.extend.actions) ? options.extend.actions : {})
    },
    getters: {
      collection: ({ collection }) => collection,
      pagination: ({ pagination }) => pagination,
      sorting: ({ sorting }) => sorting,
      loading: ({ loading }) => loading,
      loaded: ({ loaded }) => loaded,
      find: ({ collection }) => id => collection.filter((item) => item.id === id)[0],
    }
  };
}

export function createStore(resourceKey, options) {
  return new Vuex.Store(GridStore(resourceKey, options));
}
