import queryString from 'qs';
import jsona from 'lib/jsona.js';
import { ItemStore, GridStore } from 'lib/store/factory.js';
import { USER_PERMISSION, GUEST_PERMISSION } from 'lib/acl';

import tutorials from './user/tutorials';

const LOCALSTORAGE_SELECTED_ORGANISATION = 'sunrise.selected.organisation';

const resourceKey = 'user';

const ROOT_ROLE_ID = '1';

export default {
  namespaced: true,
  modules: {
    tutorials,
  },
  state: {
    me: null,
    workingOrganisation: null,
  },
  mutations: {
    setMe(state, me) {
      Vue.set(state, 'me', me);
    },
    setWorkingOrganisation(state, id) {
      Vue.set(state, 'workingOrganisation', id);
      localStorage.setItem(LOCALSTORAGE_SELECTED_ORGANISATION, id)
    }
  },
  actions: {
    setWorkingOrganisation({ commit }, id) {
      commit('setWorkingOrganisation', id);
    },
    loadMe({ state, commit, dispatch }) {
      const include = ['roles', 'organisations', 'organisations.owners', 'tutorials'];
      const qs = queryString.stringify({ include: include.join(',') }, { arrayFormat: 'index' });
      return axios.get(`/api/user/me?${qs}`)
        .then(response => {
          const me = jsona.deserialize(response.data);

          /**
           * Update seen tutorials
           */
          dispatch(
            'user/tutorials/updateSeen',
             me.tutorials.getAttributes(),
            { root: true }
          );

          const savedId = localStorage.getItem(LOCALSTORAGE_SELECTED_ORGANISATION) || null;
          const isRoot = !!me.roles.find(role => role.id === ROOT_ROLE_ID)
          let orgId = me.organisations[0].id;
          if (savedId && (isRoot || me.organisations.find(o => o.id === savedId))) {
            orgId = savedId
          }

          commit('setMe', me);
          commit('setWorkingOrganisation', orgId);

          return me;
        });
    },
    authenticate({ dispatch }, { provider }) {
      return new Promise((resolve, reject) => {
        Vue.prototype.$auth.authenticate('google')
          .then(({ data }) => {
            // Vue.prototype.$auth.setToken(data)
            setTimeout(() => {
              dispatch('loadMe')
                .then(() => resolve())
                .catch((err) => reject(err));
            }, 0)
          })
          .catch(err => reject(err));
      })
    },
    login({ dispatch }, credentials) {
      return new Promise((resolve, reject) => {
        Vue.prototype.$auth.login(credentials)
          .then(() => {
            setTimeout(() => {
              dispatch('loadMe')
                .then(() => resolve())
                .catch((err) => reject(err));
            }, 0)
          })
          .catch((err) => reject(err));
      });
    },
    registration({ dispatch }, data) {
      return new Promise((resolve, reject) => {
        Vue.prototype.$auth.register(data)
          .then(() => {
            setTimeout(() => {
              dispatch('loadMe')
                .then(() => resolve())
                .catch((err) => reject(err));
            }, 0)
          })
          .catch((err) => reject(err));
      })
    }
  },
  getters: {

    isRoot: ({ me }) => me && me.roles && !!me.roles.find(role => role.id === ROOT_ROLE_ID),

    workingOrganisation: ({ workingOrganisation }) => workingOrganisation,

    organisation: ({ workingOrganisation, me }, { isRoot }, rootState) => {
      const organisations = isRoot ? rootState.organisation.list.collection : me.organisations;
      return organisations.find(({ id }) => id === workingOrganisation) || me.organisations[0];
    },

    can: ({ me }, { isRoot }) => (permission) => {
      if (isRoot === true) {
        return true;
      }

      if (permission === GUEST_PERMISSION) {
        return !me || !Vue.prototype.$auth.isAuthenticated();
      }

      if (permission === USER_PERMISSION) {
        return me && Vue.prototype.$auth.isAuthenticated();
      }

      return me.roles
        .reduce((acc, { permissions }) => acc.concat(permissions || []))
        .indexOf(permission) === -1;
    },

  }
}
