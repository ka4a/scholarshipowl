import Vue from "vue";
import { successMessDefault } from "store/content";

export const MODAL_OPEN  = "MODAL_OPEN";
export const MODAL_CLOSE = "MODAL_CLOSE";
export const ADD_MODAL_NAME = "ADD_MODAL_NAME";

// modals
export const CANCELATION_FREE_TRIAL = "canselation-free-trial";
export const CANCELATION_BASIC = "canselation-basic";
export const SUCCESS_BASIC = "success-basic";
export const ACCOUNT_UPDATE = "account-update";
export const PROMOTION_MODAL = "promotion";
export const SENT_MESSAGE = "sent-message";
export const PAYMENT = "payment";

export default {
  namespaced: true,
  state: {
    modal: {
      overlayStatus: false,
      modalStatus: false,
      hooks: null,
      modalName: '',
      componentName: 'Default',
      content: null,
      tracking: null
    },
    modals: {
      login: false,
      freemium: false,
    }
  },
  mutations: {
    "SET_STATE" ({ modal }, { name, value }) {
      if(!modal) throw Error("Something went wrong");

      modal[name] = value;
    },
    [MODAL_OPEN] (state, modal) {
      if (!state.modals.hasOwnProperty(modal)) throw Error("Unknown modal name provided!");

      state.modals[modal] = true;
    },
    [MODAL_CLOSE] (state, modal) {
      if (!state.modals.hasOwnProperty(modal)) throw Error("Unknown modal name provided!");

      state.modals[modal] = false;
    },
    [ADD_MODAL_NAME](state, name) {
      Vue.set(state.modals, name, false);
    }
  },
  actions: {
    showModal({ commit, dispatch, rootGetters }, options) {
      let { hooks, modalName, content, tracking } = options;

      if(!modalName && !content)
        throw Error('Please modal name or content set name');

      if(modalName && typeof modalName !== 'string')
        throw Error('Please provide modal name in correct format');

      if(content && !Object.keys(content).length)
        throw Error('content object should have at list one property');

      if(hooks && hooks.before) {
        hooks.before();
      }

      if(hooks && hooks.after) {
        commit("SET_STATE", {name: 'hooks', value: hooks});
      }

      commit("SET_STATE", {name: 'overlayStatus', value: true});

      if (modalName) {
        commit("SET_STATE", {name: 'modalName', value: modalName});
      }

      if (tracking) {
        commit("SET_STATE", {name: 'tracking', value: tracking});
      }

      if(content) {
        commit("SET_STATE", {name: 'content', value: content});
      }

      // document.documentElement.className += " modal-open";
    },
    hideModal({ state, commit, getters }, playload) {
      commit("SET_STATE", {name: 'modalStatus', value: false});
      commit("SET_STATE", {name: 'modalName', value: ''});
      commit("SET_STATE", {name: 'content', value: null});
      commit("SET_STATE", {name: 'componentName', value: 'Default'})

      // document.documentElement.className =
      //     document.documentElement.className.replace(" modal-open", "");
    },
    triggerAfterHooks({ state, commit }, playload) {
      if(state.modal.hooks && state.modal.hooks.after) {
        state.modal.hooks.after(playload);
      }
    },
    getContent({ state, commit, dispatch }) {
      let { modalName, content } = state.modal;

      if(!modalName && content) {
        commit("SET_STATE", {name: 'modalStatus', value: true});
        return;
      }

      //TODO check and remove
      if(modalName.indexOf(CANCELATION_FREE_TRIAL) > -1 ||
        modalName.indexOf(CANCELATION_BASIC) > -1) {
        commit("SET_STATE", {name: 'componentName', value: 'Default'});
      }

      if(content && modalName === PROMOTION_MODAL) {
        commit("SET_STATE", {name: 'componentName', value: 'Promotion'});
        commit("SET_STATE", {name: 'modalStatus', value: true});
        return;
      }

      if(content && modalName === SENT_MESSAGE) {
        commit("SET_STATE", {name: 'componentName', value: 'SentMessage'});
        commit("SET_STATE", {name: 'modalStatus', value: true});
        return;
      }

      if(modalName === PAYMENT) {
        commit("SET_STATE", {name: 'componentName', value: 'Payment'});
        commit("SET_STATE", {name: 'modalStatus', value: true});
        return;
      }

      dispatch('content/getModalContent', modalName, {root: true})
        .then(contentServer => {
          if(content) {
            contentServer = Object.assign({}, content, contentServer);
          }

          commit("SET_STATE", {name: 'content', value: contentServer});
          commit("SET_STATE", {name: 'modalStatus', value: true});
        })
    },
    hideOverlay({ commit }) {
      commit("SET_STATE", {name: 'overlayStatus', value: false});
    },

    // TODO will remove bellow methods
    openModal({ commit, getters }, modal) {
      if (!modal) throw Error("Modal name not provided!");

      if (!getters.isOpen) {
        document.documentElement.className += " modal-open";
      }

      commit(MODAL_OPEN, modal);
    },
    closeModal({ commit, getters }, modal) {
      if (!modal) throw Error("Modal name not provided!");

      commit(MODAL_CLOSE, modal);

      if (!getters.isOpen) {
        document.documentElement.className =
          document.documentElement.className.replace(" modal-open", "");
      }
    },
    addModalName({ commit }, name) {
      if (!name) throw Error("Modal name not provided!");

      commit(ADD_MODAL_NAME, name);
    }
  },
  getters: {
    name({ modal }) {
      return !!modal && modal.name;
    },
    props({ modal }) {
      return !!modal && modal.props;
    },
    open({ modal }) {
      return !!modal && modal.isOpen;
    },
    isOpen(state) {
      for (const modal in state.modals) {
        if (state.modals.hasOwnProperty(modal) && state.modals[modal]) {
          return true;
        }
      }

      return false;
    }
  }
};
