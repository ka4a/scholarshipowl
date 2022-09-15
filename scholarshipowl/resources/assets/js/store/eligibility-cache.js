import { EligibilityCache } from "resource.js"

export const ELIGIBLE_SCHOLARSHIP_COUNT = "eligibleScholarshipCount";
export const NOT_SEEN_SCHOLARSHIP_COUNT = "notSeenScholarshipCount";

const SET_STATE = "SET_STATE";

export default {
  namespaced: true,
  state: {
    [ELIGIBLE_SCHOLARSHIP_COUNT]: null,
    [NOT_SEEN_SCHOLARSHIP_COUNT]: null
  },
  getters: {
    [NOT_SEEN_SCHOLARSHIP_COUNT]({ notSeenScholarshipCount }) {
      return notSeenScholarshipCount || 0;
    },
    [ELIGIBLE_SCHOLARSHIP_COUNT]({ eligibleScholarshipCount }) {
      return eligibleScholarshipCount || 0;
    }
  },
  mutations: {
    [SET_STATE](state, { stateName, stateData }) {
      if(!stateName || stateData === undefined)
        throw Error("Please provide correct state name or/and data");

      if(!state[stateName] === undefined)
        throw Error("Provided wrong state name");

      state[stateName] = stateData;
    }
  },
  actions: {
    getEligibilities({ commit }, fieldNames) {
      if(!fieldNames || !Array.isArray(fieldNames) || !fieldNames.length)
        throw Error("Please provide correct field set");

      return EligibilityCache
        .retrive({ fields: fieldNames })
        .then(response => {
          if(!response.body || response.body.status !== 200)
            throw Error("Server response is not success");

          const data = response.body.data;

          fieldNames.forEach(name => {
            if(!data[name]) throw Error("Server return wrong response");

            commit(SET_STATE, {stateName: name, stateData: data[name]})
          })
        })
        .catch(response => Error(response))
    },
    updateEligibilities({ commit }, ids) {
      if(!ids || !Array.isArray(ids) || !ids.length)
        throw Error("Please provide correct id set");

      return EligibilityCache
        .update({ "last_shown_scholarship_ids": ids })
        .then(() => commit(SET_STATE, {
          stateName: NOT_SEEN_SCHOLARSHIP_COUNT,
          stateData: 0
        }))
    }
  }
}