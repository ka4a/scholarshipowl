import Vue from "vue";
import { ScholarshipResource } from "resource";
import { SCHOLARSHIP_STATUS, filterByStatus, separateFavorite } from "lib/utils/filter";
import { sortByExpiration, sortByStatus,
  sortByAmount, ORDER_ASC, ORDER_DESC } from "lib/utils/sort";
import { hyphenToCamelCase } from "lib/utils/utils";

export const WON_STATUS = "WON";
export const MISSED_STATUS = "MISSED";
export const AWARDED_STATUS = "AWARDED";
export const WINNER_CHOSEN_STATUS = "WINNER CHOSEN";

export const NEW_SCHOLARSHIPS = "NEW";
export const FAVORITES_SCHOLARSHIPS = "FAVORITES";
export const SENT_SCHOLARSHIPS = "SENT";
export const CURRENT_SCHOLARSHIPS = "CURRENT_SOURCE";
export const SCHOLARSHIPS = "SCHOLARSHIPS";

const SET_SCHOLARSHIPS = "SET_SCHOLARSHIPS";
const SET_CURRENT_TAB = "SET_CURRENT_TAB";
const SET_SCHOLARSHIP = "SET_SCHOLARSHIP";
const CHANGE_SCHOLARSHIP_VALUE = "CHANGE_SCHOLARSHIP_VALUE";
const ADD_SCHOLARSHIP = "ADD_SCHOLARSHIP";
const REMOVE_SCHOLARSHIP = "REMOVE_SCHOLARSHIP";

export const REQ_TYPES = {
  FILE: 'file',
  TEXT: 'text',
  IMAGE: 'image',
  INPUT: 'input',
  SURVEY: 'survey',
  SPECIAL_ELIGIBILITY: 'special-eligibility'
}

const idNames = {
  text: "requirementTextId",
  file: "requirementFileId",
  image: "requirementImageId",
  input: "requirementInputId",
  survey: "requirementId",
  "special-eligibility": "requirementId"
};

const typesName = {
  "text": "texts",
  "file": "files",
  "image": "images",
  "input": "inputs",
  "survey": "survey",
  "special-eligibility": "specialEligibility",
}

const store = {
  namespaced: true,
  state: {
    // Full list of all eligbile scholarships
    [SCHOLARSHIPS]: [],
    [CURRENT_SCHOLARSHIPS]: [],
    [FAVORITES_SCHOLARSHIPS]: [],
    [NEW_SCHOLARSHIPS]: [],
    [SENT_SCHOLARSHIPS]: [],
    selected: null,
    selectedTab: NEW_SCHOLARSHIPS,
  },
  getters: {
    scholarships(state, getters, { list }) {
      let result = list.scholarships.result && state.selectedTab !== SENT_SCHOLARSHIPS
        ? list.scholarships.result
        : state[CURRENT_SCHOLARSHIPS];

      return result;
    },
    getScholarship(state) {
      return state.scholarshipId ?
        state.scholarships[state.scholarshipId] : null;
    },
    // TODO remove it dependencies
    // RequirementSent.vue
    getRequirementType: ({ selected }) => (requirement) => {
      if (!selected) {
        return null;
      }

      return requirement.type;
    },
    getRequirementApplication: ({ selected }) => (requirement) => {
      if (!selected) {
        return null;
      }

      const application = selected.application,
            type = requirement.type,
            types = typesName[type];

      if (application && Array.isArray(application[types])) {
        const idName = idNames[type];

        for (let i=0; i < application[types].length; i++) {
          if (application[types][i][idName] === requirement.id) {
            return application[types][i];
          }
        }
      }

      return null;
    },
  },
  mutations: {
    setApplicationStateLocaly({ selected }, opt) {
      const { id, setName } = opt;
      const applications = {...selected.application}

      delete applications.status;

      const applicationAmount = Object.values(applications).reduce((acc, itm) => (acc + itm.length), 0);

      if(applicationAmount > 1) {
        selected.application.status = 2;
        return;
      }

      if(applicationAmount === 1) {
        if(selected.application[setName].length
          && selected.application[setName][0].id === id) {
          selected.application.status = 1;
        } else {
          selected.application.status = 2;
        }
      }
    },
    setInitialApplicationState({ selected }) {
      const applications = {...selected.application}

      delete applications.status;

      const applicationAmount = Object.values(applications).reduce((acc, itm) => (acc + itm.length), 0);
      const requirementAmount = Object.values(selected.requirements).reduce((acc, itm) => (acc + itm.length), 0);

      if(applicationAmount === requirementAmount) selected.application.status = 3;
    },
    setState(state, { stateName, data }) {
      if(!stateName || typeof stateName !== "string")
        throw Error("Please provide correnct state name");

      if(!state.hasOwnProperty(stateName))
        throw Error("State name does't exist in state property");

      state[stateName] = data;
    },
    [SET_SCHOLARSHIPS](state, playload) {
      if(!playload) throw new Error("Please provide playload object");

      if(!playload.hasOwnProperty("scholarships") || typeof playload.scholarships !== "object") {
        throw new Error("Please provide scholarship list");
      }

      if(!playload.hasOwnProperty("storeName")) {
        throw new Error("Please provide scholarship list type");
      }

      state[playload.storeName] = playload.scholarships;
    },
    [SET_SCHOLARSHIP](state, scholarship) {
      if(scholarship && typeof scholarship === "object") {
        state.selected = scholarship;
      }
    },
    [SET_CURRENT_TAB](state, selectedTab) {
      if(!selectedTab || typeof selectedTab !== "string") {
        throw new Error("Please provide write selected tab identificator");
      }

      state.selectedTab = selectedTab;
    },
    ["UPDATE_SCHOLARSHIP"](state, scholarship) {
      if (scholarship && typeof scholarship === "object") {
        state[CURRENT_SCHOLARSHIPS].forEach((item, index) => {
          if(item.scholarshipId === scholarship.scholarshipId) {
            Object.assign(state[CURRENT_SCHOLARSHIPS][index], scholarship);
            state.selected = scholarship;
          }
        });
      }
    },
    [ADD_SCHOLARSHIP](state, playload) {
      if(!playload) throw Error("Please provide playload object");

      if(!playload.hasOwnProperty("storeName") || !playload.storeName || typeof playload.storeName !== "string") {
        throw Error("Please provide correct store name");
      }

      if(!playload.hasOwnProperty("scholarship") || !playload.scholarship || typeof playload.scholarship !== "object") {
        throw Error("Please provide correct value");
      }

      state[playload.storeName].unshift(playload.scholarship);

    },
    [REMOVE_SCHOLARSHIP](state, { storeName, scholarshipId }) {
      if(!storeName || typeof storeName !== "string") {
        throw Error("Please provide correct store name");
      }

      if(!scholarshipId) {
        throw Error("Please provide correct id");
      }

      state[storeName].forEach((item, index) => {
        if(item.scholarshipId === scholarshipId) {
          state[storeName].splice(index, 1);
        }
      });
    },
    [CHANGE_SCHOLARSHIP_VALUE](state, { key, value, scholarship }) {

      if(!key || typeof key !== "string") {
        throw new Error("Please provide correct key name");
      }

      if(value === undefined) {
        throw new Error("Please provide correct value");
      }

      if(!scholarship || typeof scholarship !== "object") {
        throw new Error("Please provide scholarship object");
      }

    scholarship[key] = value;
    },
  },
  actions: {
    fetchScholarships({ commit, dispatch }) {
      return ScholarshipResource.scholarships({ credentials: "include" })
        .then(response => {
          if(response.body.data.length) {
            dispatch("scholarshipInitialSequence", response.body.data);
            dispatch("separateScholarships");
          }

          return response;
        })
        .catch(err => console.log(err));
    },
    scholarshipInitialSequence({ commit, rootGetters }, scholarships) {
      const nestedValue = (obj, propName, delimeter) => {
        if(!delimeter) delimeter = "."

        return propName
          .split(delimeter)
          .reduce((acc, propName) => acc[propName], obj)
      }

      const groupBy = (objectArray, propName) => {
        return objectArray.reduce((acc, obj) => {
          let value = nestedValue(obj, propName);

          if(!acc[value]) {
            acc[value] = []
          }

          acc[value].push(obj);

          return acc;
        }, {})
      }

      let statusSequence = [
        SCHOLARSHIP_STATUS.READY_TO_SUBMIT,
        SCHOLARSHIP_STATUS.IN_PROGRESS,
        SCHOLARSHIP_STATUS.INCOMPLETE,
      ]

      let byStatus = groupBy(scholarships, "application.status");

      scholarships = statusSequence.reduce((acc, status) => {
        if(!byStatus[status]) return acc;

        let byAmount = groupBy(byStatus[status], "amount");

        let sortedAmountKeys = Object.keys(byAmount).sort((a, b) => Number(b) - Number(a));

        return acc.concat(sortedAmountKeys.reduce((acc, amountKey) => {
          let sortedByDate = byAmount[amountKey].sort((a, b) => {
            a = Date.parse(nestedValue(a, "expirationDate.date"));
            b = Date.parse(nestedValue(b, "expirationDate.date"));

            return a - b;
          });

          return acc.concat(sortedByDate);
        }, []))
      }, [])

      if(rootGetters['account/isFreemiumMVP']) {
        scholarships.sort((a, b) => {
          if(a.isAutomatic && !b.isAutomatic) return -1;
          if(!a.isAutomatic && b.isAutomatic) return 1;
          return 0;
        })
      }

      commit(SET_SCHOLARSHIPS, { storeName: SCHOLARSHIPS, scholarships });
    },
    fetchSentScholarships: ((retrived = false) => ({ state, commit }) => {
      if(retrived) return;

      return ScholarshipResource.sentScholarships()
        .then(response => {
          if(response.status === 200 && response.body.data) {
            commit(SET_SCHOLARSHIPS, {
              storeName: SENT_SCHOLARSHIPS,
              scholarships: response.body.data
            });

            retrived = true;
          }

          return response;
        });
    })(),
    separateScholarships({ state, commit }) {
      commit(SET_SCHOLARSHIPS, {
        storeName: NEW_SCHOLARSHIPS,
        scholarships: separateFavorite(state[SCHOLARSHIPS], false)
      });

      commit(SET_SCHOLARSHIPS, {
        storeName: FAVORITES_SCHOLARSHIPS,
        scholarships: separateFavorite(state[SCHOLARSHIPS], true)
      });
    },
    setCurrentScholarships({ state, commit, getters, dispatch }, scholarshipSetName) {
      if(!scholarshipSetName || typeof scholarshipSetName !== "string") {
        throw Error("Please provide proper scholarship set name");
      }

      commit(SET_SCHOLARSHIP, null);

      commit(SET_CURRENT_TAB, scholarshipSetName);

      commit(SET_SCHOLARSHIPS, {
        storeName: CURRENT_SCHOLARSHIPS,
        scholarships: state[scholarshipSetName],
      });

      commit(SET_SCHOLARSHIP, state[CURRENT_SCHOLARSHIPS][0]);
    },
    apply({ commit, dispatch }, scholarship) {
      return ScholarshipResource.apply({ scholarshipId: scholarship.scholarshipId })
        .then(response => {
          if (response.data.data) {
            dispatch('setCurrentScholarship', scholarship);

            commit(REMOVE_SCHOLARSHIP, {
              storeName: CURRENT_SCHOLARSHIPS,
              scholarshipId: response.data.data.scholarshipId
            });

            // TODO move this to beck end
            response.data.data.application.submitedDate = new Date() / 1000;

            commit(ADD_SCHOLARSHIP, {
              storeName: SENT_SCHOLARSHIPS,
              scholarship: response.data.data
            });
          }

          if (response.data.meta) {
            dispatch("account/setCredits", response.data.meta.credits, { root: true });
          }

          return response;
        })
        .catch(response => {
          throw response;
        });
    },
    applyRequirement({ commit, getters }, { requirement, details }) {
      const type = requirement.type;
      const method = "apply" + type.charAt(0).toUpperCase() + type.slice(1);
      let data = null;

      if(requirement.type === REQ_TYPES.FILE || requirement.allowFile
        || requirement.type === REQ_TYPES.IMAGE) {
        data = new FormData();

        data.append([idNames[type]], requirement.id);

        Object.keys(details).forEach(param => {
          data.append(param, details[param]);
        });
      } else {
        data = {...details, [idNames[type]]: requirement.id};
      }

      if (method in ScholarshipResource) {
        return ScholarshipResource[method](data)
          .then((response) => {
            console.log("response", response);
            if (response.data.data && response.data.data.scholarship) {
              commit("UPDATE_SCHOLARSHIP", response.data.data.scholarship);
            }

            return response;
          });
      }
    },
    deleteRequirement({ commit, getters }, requirement) {
      const application = getters.getRequirementApplication(requirement);

      if (application) {
        const type = requirement.type;
        const method = "delete" + type.charAt(0).toUpperCase() + type.slice(1);

        if (method in ScholarshipResource) {
          return ScholarshipResource[method]({ id: application.id })
            .then((response) => {
              if (response.data.data) {
                commit("UPDATE_SCHOLARSHIP", response.data.data);
              }

              return response;
            });
        }
      }
    },
    setCurrentScholarship({ state, getters, commit, rootState }, scholarship) {
      if(!scholarship) {
        commit(SET_SCHOLARSHIP, getters.scholarships[0]);
        return;
      }

      if(!state[CURRENT_SCHOLARSHIPS] || !state[CURRENT_SCHOLARSHIPS].length) {
        commit(SET_SCHOLARSHIP, null);
        return;
      }

      let scholarshipIndex = -1;

      getters.scholarships.forEach((item, index, list) => {
        if(item.scholarshipId === scholarship.scholarshipId && list[index + 1]) {
          scholarshipIndex = index;
        }
      })

      scholarshipIndex = scholarshipIndex !== -1 ? scholarshipIndex + 1 : 0;

      commit(SET_SCHOLARSHIP, getters.scholarships[scholarshipIndex]);
    },
    markFavorite({ commit, dispatch }, scholarship) {
      return ScholarshipResource.markFavorite({ id: scholarship.scholarshipId }, {})
        .then(response => {
          if(response.status === 200) {
            dispatch('setCurrentScholarship', scholarship);

            commit(CHANGE_SCHOLARSHIP_VALUE, {
              scholarship,
              key: "isFavorite",
              value: 1,
            });

            commit(ADD_SCHOLARSHIP, {
              storeName: FAVORITES_SCHOLARSHIPS,
              scholarship
            });
          }
        });
    },
    unmarkFavorite({ commit, dispatch }, scholarship) {
      return ScholarshipResource.unmarkFavorite({ id: scholarship.scholarshipId }, {})
        .then(response => {
          if(response.status === 200) {
            dispatch('setCurrentScholarship', scholarship);

            commit(CHANGE_SCHOLARSHIP_VALUE, {
              scholarship,
              key: "isFavorite",
              value: 0,
            });

            commit(ADD_SCHOLARSHIP, {
              storeName: NEW_SCHOLARSHIPS,
              scholarship
            });
          }
        });
    }
  },
};

export default store;