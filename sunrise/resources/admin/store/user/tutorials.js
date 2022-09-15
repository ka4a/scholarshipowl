export default {
  namespaced: true,
  state: {
    open: {},
    seen: {},
  },
  mutations: {
    setOpen(state, { tutorial, isOpen }) {
      Vue.set(state.open, tutorial, isOpen);
    },
    setSeen(state, { tutorial, isSeen }) {
      Vue.set(state.seen, tutorial, isSeen);
    }
  },
  actions: {
    open({ state, commit }, tutorial) {
      if (!state.seen[tutorial]) {
        commit('setOpen', { tutorial, isOpen: true });
      }
    },
    close({ state, commit, dispatch, rootState }, tutorial) {
      /**
       * If tutorial wasn't seen mark as seen on backend.
       */
      if (!state.seen[tutorial]) {
        const data = { attributes: { [tutorial]: true } };
        axios.patch(`/api/user_tutorial/${rootState.user.me.id}`, { data })
          .then(({ data }) => {
            if (data && data.data && data.data.attributes) {
              dispatch('updateSeen', data.data.attributes);
            }
          });
      }

      commit('setOpen', { tutorial, isOpen: false });
    },
    updateSeen({ commit, state }, seen) {
      if (typeof seen === 'object') {
        Object.keys(seen).forEach((tutorial) => {
          commit('setSeen', { tutorial, isSeen: !!seen[tutorial] });
        })
      }
    }
  },
  getters: {
    active : ({ open }) => {
      return Object.keys(open).filter(tutorial => open[tutorial])[0];
    }
  }
}
