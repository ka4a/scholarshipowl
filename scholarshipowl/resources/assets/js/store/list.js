import { isEqual } from 'lodash';
import { shallowObjectCopy } from "lib/utils/utils";
import { CURRENT_SCHOLARSHIPS } from 'store/scholarships';
import { OPTION_ANY, applyFilters } from "lib/utils/filter";
import { ORDER_ASC, ORDER_DESC, SORT_DATE, SORT_PROGRESS, applySorting } from "lib/utils/sort";

const MAILBOX_PREFIX = 'mailbox',
      SCHOLARSHIPS_PREFIX = 'scholarships';

function setState(state, playload) {
  let { nameSpace, value } = playload;

  nameSpace = nameSpace.split('/');

  let lastItem = nameSpace[nameSpace.length - 1];

  nameSpace.splice(nameSpace.length - 1, 1)
  nameSpace.forEach(path => state = state[path]);

  state[lastItem] = value;
}

const defaultFilters = {
  mailbox: {
    query: '',
  },
  scholarships: {
    status: [],
    deadline: {
      from: "",
      to: ""
    },
    amount: [0, 5000],
    essayRequired: OPTION_ANY,
    isRecurrent: OPTION_ANY,
  }
}

const defaultSortingParam = {
  mailbox: {
    order: ORDER_ASC,
    sortBy: SORT_DATE,
  },
  scholarships: {
    order: ORDER_ASC,
    sortBy: SORT_PROGRESS,
  }
}

export default {
  namespaced: true,
  state: {
    mailbox: {
      sort: {
        ...shallowObjectCopy(defaultSortingParam.mailbox)
      },
      filter: {
        ...shallowObjectCopy(defaultFilters.mailbox)
      },
      result: null,
      sorted: false,
      filtered: false
    },
    scholarships: {
      sort: {
        ...shallowObjectCopy(defaultSortingParam.scholarships)
      },
      filter: {
        ...shallowObjectCopy(defaultFilters.scholarships)
      },
      result: null,
      sorted: false,
      filtered: false
    }
  },
  getters: {
    [`${MAILBOX_PREFIX}/set`](state, getters, rootState) {
      return rootState[MAILBOX_PREFIX][rootState[MAILBOX_PREFIX].mailState];
    },
    [`${SCHOLARSHIPS_PREFIX}/set`](state, getters, rootState) {
      return rootState[SCHOLARSHIPS_PREFIX][CURRENT_SCHOLARSHIPS];
    },
  },
  mutations: {
   setState
  },
  actions: {
    setFilterParam({ state, commit, getters }, playload) {
      let { nameSpace, filterBy, parameter} = playload;

      commit('setState', { nameSpace: `${nameSpace}/filter/${filterBy}`, value: parameter });
    },
    mutateList({ state, commit, getters }, nameSpace) {
      let source = getters[`${nameSpace}/set`],
        sortBy = state[nameSpace].sort.sortBy === null ? SORT_DATE : state[nameSpace].sort.sortBy,
        order = state[nameSpace].sort.order,
        result = applySorting(applyFilters(source, state[nameSpace].filter), sortBy, order);

      commit('setState', { nameSpace: `${nameSpace}/result`, value: result });
    },
    applyFilter({ state, commit, getters, dispatch }, nameSpace) {
      let equal = isEqual(defaultFilters[nameSpace], state[nameSpace].filter);

      dispatch('mutateList', nameSpace);

      commit('setState', { nameSpace: `${nameSpace}/filtered`, value: !equal});
    },
    resetFilter({ state, commit, getters, dispatch }, nameSpace) {
      let defaultValues = shallowObjectCopy(defaultFilters[nameSpace]);

      commit('setState', { nameSpace: `${nameSpace}/filter`, value: defaultValues });
      dispatch('mutateList', nameSpace);
      commit('setState', { nameSpace: `${nameSpace}/result`, value: null});
      commit('setState', { nameSpace: `${nameSpace}/filtered`, value: false });
    },
    sortList({ state, commit, getters, dispatch }, playload) {
      let { nameSpace, sortBy, order } = playload;

      commit('setState', { nameSpace: `${nameSpace}/sort/sortBy`, value: sortBy });
      commit('setState', { nameSpace: `${nameSpace}/sort/order`, value: order });
      dispatch('mutateList', nameSpace);
      commit('setState', { nameSpace: `${nameSpace}/sorted`, value: true });
    },
    resetSort({ commit, dispatch }, nameSpace) {
      let defaultValues = shallowObjectCopy(defaultSortingParam[nameSpace]);

      commit('setState', { nameSpace: `${nameSpace}/sort`, value: defaultValues });
      dispatch('mutateList', nameSpace);
      commit('setState', { nameSpace: `${nameSpace}/result`, value: null});
      commit('setState', { nameSpace: `${nameSpace}/sorted`, value: false });
    },
    reset({ commit, dispatch }, nameSpace) {
      let defaulSortValues = shallowObjectCopy(defaultSortingParam[nameSpace]),
          defaulFiltertValues = shallowObjectCopy(defaultFilters[nameSpace]);

      commit('setState', { nameSpace: `${nameSpace}/sort`, value: defaulSortValues });
      commit('setState', { nameSpace: `${nameSpace}/filter`, value: defaulFiltertValues });
      dispatch('mutateList', nameSpace);
      commit('setState', { nameSpace: `${nameSpace}/result`, value: null});
      commit('setState', { nameSpace: `${nameSpace}/sorted`, value: false });
      commit('setState', { nameSpace: `${nameSpace}/filtered`, value: false });
    }
  }
}