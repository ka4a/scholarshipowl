const promises = {};

export default {
  namespaced: true,
  state: {
    open: {
      organisationSetup: false,
    },
  },
  mutations: {
    setOpen(state, { modal, isOpen }) {
      Vue.set(state.open, modal, isOpen);
    },
  },
  actions: {
    open({ state, commit }, modal) {
      commit('setOpen', { modal, isOpen: true });
      return (new Promise((resolve, reject) => {
        promises[modal] = { resolve, reject }
      }))
        .finally(() => {
          commit('setOpen', { modal, isOpen: false });
          delete promises[modal];
        })
        // .then((v) => v, (e) => {});
        // .finally(() => commit('setOpen', { modal, isOpen: false }))
        // .catch((e) => e);
      // new Promise((resolve, reject) => {
      //   promises[modal] = { resolve, reject };
      // });
    },
    resolve(ctx, { modal, value }) {
      if (promises[modal] && promises[modal].resolve) {
        promises[modal].resolve(value);
        delete promises[modal];
      }
    },
    reject(ctx, { modal, value }) {
      if (promises[modal] && promises[modal].reject) {
        promises[modal].reject();
        delete promises[modal];
      }
    },
    close({ state, dispatch }, modal) {
      dispatch('reject', { modal, value: false });
    }
  },
  getters: {
    active : ({ open }) => {
      return Object.keys(open).filter(modal => open[modal])[0];
    },
  },
};
