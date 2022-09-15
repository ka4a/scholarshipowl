import { FSetResource } from "resource";

const CONTENT_SET = "contentSet";
const DESKTOP_PAYMENT_SET = "desktopPaymentSet";
const MOBILE_PAYMENT_SET = "mobilePaymentSet";

const SET_STATE = "SET_STATE";

export default {
  namespaced: true,
  state: {
    id: undefined,
    name: undefined,
    [CONTENT_SET]: null,
    [DESKTOP_PAYMENT_SET]: null,
    [MOBILE_PAYMENT_SET]: null
  },
  getters: {
    plansPageContent({ contentSet }) {
      if(!contentSet) return null;

      return {
        carouselItemCnt: contentSet.ppCarouselItemsCnt,
        headerText: contentSet.ppHeaderText,
        headerSubText: contentSet.ppHeaderText2
      }
    },
    scholarshipsPageContent({ contentSet }) {
      if(!contentSet) return null;

      const { applicationSentTitle, applicationSentDescription,
        noCreditsTitle, noCreditsDescription} = contentSet;

      return {
        applicationSentTitle,
        applicationSentDescription,
        noCreditsTitle,
        noCreditsDescription,
      }
    }
  },
  mutations: {
    [SET_STATE](state, { stateName, stateData }) {
      if(!stateName || stateData === undefined)
        throw Error("Please provide correct state name or/and data");

      if(!state.hasOwnProperty(stateName))
        throw Error("Provided wrong state name");

      state[stateName] = stateData;
    }
  },
  actions: {
    /**
     * Retrive data from fset controller and set it to
     * fset storage
     * @param  {Funciton} options.commit store commit function
     * @param  {Array|undefined} fields  Array of field names
     * @return {Promise} Request representation promise
     */
    getFsetData({ commit }, fields) {
      fields = fields || [];

      return FSetResource
        .fset({fields})
        .then(response => {
          if(response.body && response.body.status === 200) {
            const data = response.body.data;

            Object.keys(data)
              .forEach(fieldName => commit(SET_STATE, {
                stateName: fieldName,
                stateData: data[fieldName]
              }))
          }

          return response;
        })
    }
  }
}

