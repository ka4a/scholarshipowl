import { AccountResource, SubscriptionResource } from "../resource";
import { setState } from "lib/utils/store";

const FREEMIUM_MVP = "freemium-mvp";

let SOWLStorage = window.SOWLStorage || window.SOWLStorageOptimized;

export const COUNTRY_ID_USA = 1;

const removeDuplicates = (a, b) => {
  return a.filter(item => b.indexOf(item) === -1)
}

export default {
  namespaced: true,
  state: {
    account: typeof SOWLStorage !== undefined ? SOWLStorage.account : null,
    loading: false,
    membership: null,
    profile: null,
    scholarship: null,
    mailbox: null,
  },
  mutations: {
    setState,
    ["SET_ACCOUNT"] (state, account) {
      state.account = account;
    },
    ["PROFILE_FIELD_UPDATE"] (state, { field, value }) {
      if(!state.profile) state.profile = {};

      state.profile[field] = value;
    },
    ["SET_CREDITS"] (state, credits) {
      state.membership.credits = credits;
    },
    ["READ_MAIL"](state) {
      if(!state.mailbox || !state.mailbox.inbox) return;

      const inbox = state.mailbox.inbox;

      if(inbox.unread > 0) {
        inbox.unread = inbox.unread - 1;
      }

      if(inbox.read < inbox.total) {
        inbox.read = inbox.read + 1;
      }
    }
  },
  actions: {
    setCredits ({ commit }, credits) {
      commit("SET_CREDITS", credits);
    },
    updateProfile ({ state, commit }, details) {
      return AccountResource
        .profileUpdate({ id: state.account.accountId }, details)
        .then((response) => {
          if (response.status === 200 && response.data.data) {
            Object.keys(response.data.data).forEach(field => {
              commit("PROFILE_FIELD_UPDATE", {
                field, value: response.data.data[field]
              });
            });
          }

          return response;
        })
    },
    accountLogin ({ commit }, account) {
      if (!account.accountId) {
        throw new Error("Account id missing for authorization!");
      }

      commit("SET_ACCOUNT", account);

      if(account.profile && Object.keys(account.profile).length) {
        Object.keys(account.profile).forEach(field => {
          commit("PROFILE_FIELD_UPDATE", {
            field, value: account.profile[field]
          });
        });
      }
    },
    registration ({ dispatch }, details) {
      return Promise.resolve()
        .then(() => {
          return AccountResource.register(details)
            .then((response) => {
              if (response.status === 200 && response.data.data) {
                dispatch("accountLogin", response.data.data);
              }

              return response;
            })
            .catch((response) => {
              if (response.status === 403) {
                window.location = "/scholarships";
                return;
              }

              throw response;
            });
        });
    },
    loginAttempt ({ dispatch }, credentials) {
      return AccountResource.login(credentials)
        .then((response) => {
          if (response.status === 200 && response.data.data && response.data.meta) {
            dispatch("accountLogin", response.data.data);
            window.location = response.data.meta.redirect || "/my-account";
          }
        });
    },
    updateField({ commit }, {fieldName, data}) {
      if(!fieldName || typeof fieldName !== "string")
        throw Error('Please provide correct fieldName');

      commit("setState", {
        stateName: fieldName,
        data
      });
    },
    fetchAndUpdateField({ commit, dispatch }, resources) {
      if(!resources || !Array.isArray(resources))
        throw Error('Please provide resources!');

      commit("setState", {stateName: "loading", data: true});

      return AccountResource.accountInfo({fields: resources})
        .then(response => {
          if(response && response.status === 200) {
            resources.forEach(resourceName => {
              dispatch("updateField", {
                fieldName: resourceName,
                data: response.body.data[resourceName] || null
              })
            })

            commit("setState", {stateName: "loading", data: false});

            return response;
          }
        })
        .catch(response => {
          console.log(response);
          commit("setState", {stateName: "loading", data: false});
        })
    },
    fetchData: (prevRequestSources => ({ state, dispatch }, resources) => {
      if(!resources) throw Error('Please provide resources!');

      return dispatch('fetchAndUpdateField', resources);
    })(),
    subscriptionCancel({ dispatch }, id) {
      return SubscriptionResource.cancel({ id }, {})
        .then(response => {
          if(response && response.status === 200) {
            return dispatch("fetchAndUpdateField", ["membership"])
          }

          return response;
        })
    }
  },
  getters: {
    guest ({ account }) {
      return account === null;
    },
    authenticated ({ account }) {
      return account !== null;
    },
    isUSA (state, { profile }) {
      return profile && profile.country
        ? !profile.country.name || profile.country.name === "USA"
        : !SOWLStorage.settings || SOWLStorage.settings.uc === "US";
    },
    profile ({ account }) {
      return account ? account.profile : null;
    },
    marketing ({ account }) {
      return account !== null ? account.marketing : null;
    },
    companyDetails ({ account }) {
      return account !== null ? account.companyDetails : null;
    },
    accountId ({ account }) {
      return account && account.accountId
        ? account.accountId
        : null;
    },
    isMember ({ membership }) {
      return membership && membership.isMember;
    },
    unreadInbox({ mailbox }) {
      return mailbox && mailbox.inbox && mailbox.inbox.unread
        ? mailbox.inbox.unread : null;
    },
    loading({ loading }) {
      return loading;
    },
    mailbox({ mailbox }) {
      return mailbox || null;
    },
    profile({ profile }) {
      return profile || null;
    },
    account({ account }) {
      return account || null;
    },
    application({ application }) {
      return application || null;
    },
    membership({ membership }) {
      return membership || null;
    },
    scholarship({ scholarship }) {
      return scholarship || null;
    },
    socialAccount({ socialAccount }) {
      return socialAccount || null;
    },
    scholarshipCount(state, { scholarship }) {
      return scholarship && scholarship.eligibleCount
        ? scholarship.eligibleCount
        : null;
    },
    scholarshipAmount(state, { scholarship }) {
      return scholarship && scholarship.eligibleAmount
        ? scholarship.eligibleAmount
        : null;
    },
    isFreemium({ membership }) {
      return membership && membership.isFreemium
        && !membership.packageAlias;
    },
    isFreemiumMVP({ membership }) {
      return membership && membership.isFreemium
        && membership.packageAlias === FREEMIUM_MVP
    },
    gender({ profile }) {
      return profile && profile.gender
        ? profile.gender
        : null;
    }
  }
};
